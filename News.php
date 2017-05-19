<?php

/**
 * RESTful控制器
 * @author 刘健 <code.liu@qq.com>
 */
class News extends REST_Controller
{

    public function _index()
    {
        $this->response(['errcode' => 0, 'errmsg' => 'index']);
    }

    public function _create()
    {
        $this->response(['errcode' => 0, 'errmsg' => 'create']);
    }

    public function _save($data)
    {
        $this->response(['errcode' => 0, 'errmsg' => 'save', 'data' => $data]);
    }

    public function _read($id)
    {
        $this->response(['errcode' => 0, 'errmsg' => 'read', 'id' => $id]);
    }

    public function _edit($id)
    {
        $this->response(['errcode' => 0, 'errmsg' => 'edit', 'id' => $id]);
    }

    public function _update($id, $data)
    {
        $this->response(['errcode' => 0, 'errmsg' => 'update', 'id' => $id, 'data' => $data]);
    }

    public function _delete($id)
    {
        $this->response(['errcode' => 0, 'errmsg' => 'delete', 'id' => $id]);
    }

    // 析构
    public function __destruct()
    {
        // 回收PUT上传的临时文件
        parent::__destruct();
    }

}
