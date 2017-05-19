## 简介

为 CodeIgniter 框架扩展 RESTful API 的支持，RESTful 控制器基类。

## 使用说明

### 第 1 步

将以下两个文件 copy 至 `application/core` 目录中。

```
MY_Controller.php
REST_Controller.php
```

> MY_Controller.php 的前缀要与 config.php 内的 subclass_prefix 配置项一至

### 第 2 步

按如下范例建立你的控制器：

```
class News extends REST_Controller
{

    public function _index()
    {
        echo 'index';
    }

    public function _create()
    {
        echo 'create';
    }

    public function _save($data)
    {
        echo 'save';
    }

    public function _read($id)
    {
        echo 'read';
    }

    public function _edit($id)
    {
        echo 'edit';
    }

    public function _update($id, $data)
    {
        echo 'update';
    }

    public function _delete($id)
    {
        echo 'delete';
    }

    // 析构
    public function __destruct()
    {
    	// 回收PUT上传的临时文件
        parent::__destruct();
    }

}
```