<?php

namespace H5p;

/**
* 自定义二维码参数
* 还未测试 红包发放
*/
class QRCode extends Common
{
    

    function __construct()
    {
        parent::__construct();
    }

    public function doit($qrcode_arg,$event,$openid){

        list($id,$modelid_s,$modelid_t) = explode(':', $qrcode_arg);

        if(empty($id)){
            self::$errinfo = 'ID为空，二维码参数错误';
            return false;
        }

        // 获取
        $data = $this->get_content_data($id);
        

        if($data !== false){
            
            // 统计信息
            $this->statistics($modelid_s,$event,$id,$openid);

            // 接下来根据$data 中的字段进行处理
            // 要约定好各个字段的意义

            if($data['wenbenxinxi'] != ''){

                // 文本信息不为空，像用户发送文本信息
                $re = $this->send_text($data['wenbenxinxi'],$openid);
            }

            if($data['tupianxinxi'] != ''){
                // 图片信息不为空，像用户发送图片信息

                $this->send_image($data['tupianxinxi'],$openid);
            }

            if($data['tuwenyibiaoti'] != '' || $data['tuwenerbiaoti'] != '' || $data['tuwensanbiaoti'] != ''){

                $news = array();

                if($data['tuwenyibiaoti'] != '' && $data['tuwenyitupian'] != '' && $data['tuwenyilianjie'] != ''){

                    $news_one = array(
                        'title' => $data['tuwenyibiaoti'],
                        'image' => $data['tuwenyitupian'],
                        'url'   => $data['tuwenyilianjie']
                    );
                    $news[] = $news_one;
                }

                if($data['tuwenerbiaoti'] != '' && $data['tuwenertupian'] != '' && $data['tuwenerlianjie'] != ''){

                    $news_one = array(
                        'title' => $data['tuwenerbiaoti'],
                        'image' => $data['tuwenertupian'],
                        'url'   => $data['tuwenerlianjie']
                    );
                    $news[] = $news_one;
                }

                if($data['tuwensanbiaoti'] != '' && $data['tuwensantupian'] != '' && $data['tuwensanlianjie'] != ''){

                    $news_one = array(
                        'title' => $data['tuwensanbiaoti'],
                        'image' => $data['tuwensantupian'],
                        'url'   => $data['tuwensanlianjie']
                    );
                    $news[] = $news_one;
                }

                if(!empty($news)){
                    $this->send_news($news,$openid);
                }
                
            }

            // 独立链接
            if($data['lianjiebiaoti'] != '' && $data['lianjiedizhi'] != ''){
                // 文本信息不为空，像用户发送文本信息
                $this->send_link($data['lianjiebiaoti'],$data['lianjiedizhi'],$openid);
            }


            if ($data['hongbaojine'] != '' && $data['hongbaozhufuyu'] != '' && $data['hongbaohuodong'] != '' && $data['hongbaobeizhu'] != '') {
                // 要判断该用户之前有没有获得过红包

                // 其他二维码根据设定的二维码红包领取次数和红包领取记录来发放。
                // 红包次数
                $hb_times = (int)$data['hongbaocishu'];

                if($hb_times != 0){

                    // 零次不发
                    $check_data = $this->get_form_data($modelid_t,"openid='".$openid."' AND qrid='".$id."'");

                    $hb_num = count($check_data);

                    if($hb_num < $hb_times){
                        // 已获红包次数必须小于允许次数
                        // 开始发放红包
                        // 发放红包
                        $hb_one = (int)$data['hongbaojine'];
                        $hb_two = (int)$data['hongbaojinetwo'];

                        // 如果红包金额和红包金额2数字不相等，则判断为发送随机红包
                        // 相等则判断为发送固定红包

                        if($hb_one != $hb_two) {

                            if($hb_one > $hb_two){

                                $packet_num = mt_rand($hb_two,$hb_one);

                            }else{

                                $packet_num = mt_rand($hb_one,$hb_two);

                            }

                            Log::write(var_export($packet_num, true), 'wc-lm-num');

                        }else{

                            $packet_num = $hb_one;

                        }

                        $setting = array();
                        // 红包发送者
                        $setting['send_name'] = $data['hongbaofasongzhe']?$data['hongbaofasongzhe']:'';
                        // 红包金额
                        $setting['total_amount'] = $packet_num;

                        $setting['total_num'] = 1;
                        $setting['wishing'] = $data['hongbaozhufuyu'];
                        $setting['act_name'] = $data['hongbaohuodong'];
                        $setting['remark'] = $data['hongbaobeizhu'];

                        $re = $this->send_redpack($setting,$openid);

                        // $re = wx_luck_money('吃喝天地会',$u_openid,$packet_num,1,$data['hongbaozhufuyu'],$data['hongbaohuodong'],$data['hongbaobeizhu']);
                        if($re){

                            // 插入红包记录
                            if($modelid_r != 0){
                                // 字段数据
                                $inp_data['openid'] = $openid;
                                $inp_data['shijian'] = time();
                                $inp_data['hongbaojine'] = $packet_num;
                                $inp_data['qrid'] = $qrid;

                                $this->set_form_data($inp_data,$modelid_t);
                            }

                        }else{
                            // 发送红包失败，通知指定用户
                            // 向管理员发送通知
                            // $message = "帐号没钱发红包了！";
                            // $staff->send($message)->to('oGCaguPc_K8SLeZUMSjAB1eptRsA');
                            // 向用户发送提醒
                            $message = "荷包银子不够，待我去银号取钱！稍后请联系客服领取奖励！";
                            $this->send_text($message,$openid);
                        }
                    }
                }
                
            }

            return true;

        }else{
            return false;
        }

    }

    public function create($qrcode_arg){
        // $qrcode_arg 必须以1:2:3这样的方式分隔开各个参数
        // 默认第一个为文档$id
        // 第二个为统计表单Modelid_s
        // 第三个为红包记录表单Modelid_t


        $qrcode = new \Overtrue\Wechat\QRCode($this->app_id, $this->app_secret);

        $qrname = 'h5p-'.$qrcode_arg;

        $result = $qrcode->forever($qrname);// 或者 $qrcode->forever("foo");

        // $ticket = $result->ticket; // 或者 $result['ticket']

        // 下载二维码到本地服务器
        // $qrcode->download($ticket, __DIR__ . '/code.jpg');

        // $url = $result->url;

        return $result;

    }

    // 把二维码扫描数据放到指定表单中
    private function statistics($modelid,$event,$qrid,$openid){
        // 插入红包记录
        if($modelid != 0){
            // 字段数据
            $inp_data['openid'] = $openid;
            $inp_data['event'] = $event;
            $inp_data['qrid'] = $qrid;

            return $this->set_form_data($inp_data,$modelid);
        }

        return false;
    }
    
}