<?php

namespace H5p;

/**
* 自定义二维码参数
*/
class QRCode extends Common
{
    

    function __construct()
    {
        parent::__construct();
    }

    public function doit($content_id,$form_modelid){

        // 获取
        $data = $this->get_content_data($id);

        if($data !== false){
            // 接下来根据$data 中的字段进行处理
            // 要约定好各个字段的意义

        }
    }

    // 把二维码扫描数据放到指定表单中
    private function statistics($modelid){
        
    }
    
}