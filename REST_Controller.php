<?php

/**
 * rest控制器基类
 * @author 刘健 <code.liu@qq.com>
 */
class REST_Controller extends CI_Controller
{

    // PUT上传的文件信息
    private static $uploadFiles = [];

    // CI的重映射方法
    public function _remap()
    {
        $patterns = [
            'index'       => ['/^\/([\w]+)$/', 'GET'],
            'create'      => ['/^\/([\w]+)\/create$/', 'GET'],
            'save'        => ['/^\/([\w]+)$/', 'POST'],
            'read'        => ['/^\/([\w]+)\/([\w]+)$/', 'GET'],
            'edit'        => ['/^\/([\w]+)\/([\w]+)\/edit$/', 'GET'],
            'update'      => ['/^\/([\w]+)\/([\w]+)$/', 'PUT'],
            'post_update' => ['/^\/([\w]+)\/([\w]+)\/put$/', 'POST'],
            'delete'      => ['/^\/([\w]+)\/([\w]+)$/', 'DELETE'],
            'get_delete' => ['/^\/([\w]+)\/([\w]+)\/delete$/', 'GET'],
        ];
        foreach ($patterns as $requestType => $value) {
            list($pattern, $method) = $value;
            if (preg_match($pattern, $_SERVER['PATH_INFO'], $matches) && $method == $_SERVER['REQUEST_METHOD']) {
                // 提取数据
                switch ($requestType) {
                    case 'index':
                        $this->_index();
                        break;
                    case 'create':
                        $this->_create();
                        break;
                    case 'save':
                        $data = $_POST;
                        $this->_save($data);
                        break;
                    case 'read':
                        $id = $matches[2];
                        $this->_read($id);
                        break;
                    case 'edit':
                        $id = $matches[2];
                        $this->_edit($id);
                        break;
                    case 'update':
                        $id   = $matches[2];
                        $data = self::parseBody(file_get_contents('php://input'));
                        $this->_update($id, $data);
                        break;
                    case 'post_update':
                        $id   = $matches[2];
                        $data = $_POST;
                        $this->_update($id, $data);
                        break;
                    case 'delete':
                        $id = $matches[2];
                        $this->_delete($id);
                        break;
                    case 'get_delete':
                        $id = $matches[2];
                        $this->_delete($id);
                        break;
                }
                return;
            }
        }
        show_404();
    }

    // 解析HTTP的body
    private static function parseBody($body)
    {
        $boundary = substr($body, 0, strpos($body, "\r\n"));
        if (strlen($boundary) < 25) {
            return [];
        }
        // 去尾部边界
        $body = str_replace($boundary . "--\r\n", '', $body);
        // 分割
        $body = explode($boundary . "\r\n", $body);
        // 转化
        $bodyData = [];
        $bodyPut  = [];
        foreach ($body as $item) {
            if (empty($item)) {
                continue;
            }
            $itemBoundary = strpos($item, "\r\n\r\n");
            $headers      = substr($item, 0, $itemBoundary);
            $headers      = self::parseBodyHeaders($headers);
            if (!isset($headers['name'])) {
                continue;
            }
            $length                               = strlen($item) - ($itemBoundary + 4) - 2;
            $itemBody                             = substr($item, $itemBoundary + 4, $length);
            $bodyData[$headers['name']]['header'] = $headers;
            $bodyData[$headers['name']]['body']   = $itemBody;
            if (!isset($headers['filename'])) {
                $bodyPut[$headers['name']] = $itemBody;
            }
        }
        // 提取文件
        self::fetchFiles($bodyData);
        // 回收内存
        $body     = null;
        $bodyData = null;
        // 返回
        return $bodyPut;
    }

    // 提取文件并填充 $_FILES
    private static function fetchFiles(&$data)
    {
        $uploadTmpDir = ini_get('upload_tmp_dir');
        foreach ($data as $name => $item) {
            if (isset($item['header']['filename'])) {
                // 保存文件
                $filename = $uploadTmpDir . '/' . uniqid('php') . '.tmp';
                file_put_contents($filename, $item['body']);
                // 填充 $_FILES 数组
                $_FILES[$name] = [
                    'name'     => $item['header']['filename'],
                    'type'     => $item['header']['content-type'],
                    'tmp_name' => $filename,
                    'error'    => 0,
                    'size'     => strlen($item['body']),
                ];
                unset($data[$name]);
                // 保存上传的文件信息
                self::$uploadFiles[] = $filename;
            }
        }
    }

    // 解析HTTP的body内的header
    private static function parseBodyHeaders($headersString)
    {
        $headersString = strtolower(str_replace(["\r\n", ' ', '"', ':', ';'], ['&', '', '', '=', '&'], $headersString));
        parse_str($headersString, $headers);
        return $headers;
    }

    // 析构
    public function __destruct()
    {
        // 回收上传的临时文件
        foreach (self::$uploadFiles as $filename) {
            unlink($filename);
        }
    }

}
