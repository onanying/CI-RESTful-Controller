<?php

/**
 * RESTful控制器
 * @author 刘健 <code.liu@qq.com>
 */
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
