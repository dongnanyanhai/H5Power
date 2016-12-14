<?php

class TestController extends Plugin {
        
    public function __construct() {
        parent::__construct();
    }

    public function testAction(){
        $QRCode = new H5p\QRCode();
        // var_dump($QRCode->get_form_data(2,"openid = 'ahaitest' AND qrid = '1'"));
        // var_dump($QRCode->get_form_data(2,'',2));
        var_dump($QRCode->doit('1:2:0','SCAN','oFrGvt5VighehGtB1eC1GQ7j_Y2g'));
        // var_dump($QRCode->get_errinfo());

        // Common 类方法测试
        // $h5p = new H5p\Common();

        // $h5p->send_text("阿海你好",'oFrGvt5VighehGtB1eC1GQ7j_Y2g');

        // $re = $h5p->send_image('views/admin/images/admin-jishu.png','oFrGvt5VighehGtB1eC1GQ7j_Y2g');

        // $h5p->send_link("阿海你好",'http://www.qq.com','oFrGvt5VighehGtB1eC1GQ7j_Y2g');

        // $news = array(
        //     0 => array(
        //         'title'=>'标题1',
        //         'image'=>'views/admin/images/admin-jishu.png',
        //         'url'=>'http://www.qq.com',
        //     ),
        //     1 => array(
        //         'title'=>'标题2',
        //         'image'=>'views/admin/images/admin-jishu.png',
        //         'url'=>'http://www.qq.com',
        //     ),
        //     2 => array(
        //         'title'=>'标题3',
        //         'image'=>'views/admin/images/admin-jishu.png',
        //         'url'=>'http://www.qq.com',
        //     ),
        //     3 => array(
        //         'title'=>'标题4',
        //         'image'=>'views/admin/images/admin-jishu.png',
        //         'url'=>'http://www.qq.com',
        //     )
        // );

        // $re = $h5p->send_news($news,'oFrGvt5VighehGtB1eC1GQ7j_Y2g');

        // var_dump($re);
    }
}