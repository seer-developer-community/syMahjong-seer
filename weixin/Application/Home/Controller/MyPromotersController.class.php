<?php
// 我的推广员
namespace Home\Controller;
use Think\Controller;
header('Content-type:text/html;charset=UTF-8');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class MyPromotersController extends Controller {
    //json
    function json_encode($array)
    {
        if(version_compare(PHP_VERSION,'5.4.0','<')){
            $str = json_encode($array);
            $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i",function($matchs){
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
            },$str);
            return $str;
        }else{
            return json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }
    //普通玩家查询出来的数据
    /*
     * 需要得到的数据有:
     * head_pic(微信头像)
     * wx_name(游戏昵称)
     *(推广收益)
     * */
    public function index1(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("user_list")
            ->field("next_uids")
            ->where("uid='$uid' and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5')")
            ->select();//echo "<pre>";var_dump($rows);
        //查询出来的数据只有一个
        if(empty($rows)){
            //$str = "";
            echo $this->json_encode("");
        }
        else {
            $uids = $rows[0]['next_uids'];//echo "<pre>";var_dump($uids);
            $arr = json_decode($uids);//echo "<pre>";var_dump($arr);
            $count = count($arr);
            $str = "";
            for($i=0;$i<$count;$i++){
                $str .= "gen_uid=".$arr[$i]." or ";
            }
            $where = substr($str,0,-4);
            //echo "<pre>";var_dump($arr);
            //SELECT SUM(self_profit+pre_profit+pre_pre_profit) as sum FROM profit_log WHERE gen_uid=100038 or gen_uid=100039 GROUP BY gen_uid
            //SELECT SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.last_login_time,user_list.head_pic,user_list.uid,user_list.wx_name FROM profit_log LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid WHERE profit_log.gen_uid=100038 or profit_log.gen_uid=100039 GROUP BY profit_log.gen_uid
            //date_format(last_login_time,'%Y-%m-%d') as last_login_time

            /*$rowss = M("profit_log")
                //->field("next_uids")
                ->where($where)
                ->select();echo "<pre>";var_dump($rowss);*/
            $rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time,user_list.examine,(case proxy_lv when 1 then '普通玩家' when 2 then '金豆玩家' when 3 then '初级推广员' when 4 then '中级推广员' else '高级推广员' end) as whether")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where($where)
                ->group("profit_log.gen_uid")
                ->select();
            $json = $this->json_encode($rowss);
            echo $json;
        }

    }

    public function index(){
        $phone = I('post.phone');
        $uidInput = I('post.uid');
        //$uidInput = "100038";
        if(empty($uidInput)){
            $row = M("user_list")->where("phone_number='13200231520'")->select();
            $uid = $row[0]['uid'];
            $rows = M("user_list")
                ->field("next_uids")
                ->where("uid='$uid'")
                ->select();
            $uids = $rows[0]['next_uids'];
            $uids = substr($uids,0,-1);
            $uids = substr($uids,1);
            $rowu = M("user_list")
                ->where("uid in ($uids) and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5')")
                ->select();
            if (empty($rowu)){
                echo $this->json_encode("");
            }
            else {
                $arr = json_decode($uids);
                $count = count($rowu);
                $str = "";
                for($i=0;$i<$count;$i++){
                    $str .= "gen_uid=".$rowu[$i]['uid']." or ";
                }
                $where = substr($str,0,-4);
                $rowss = M("profit_log")
                    ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time,user_list.examine,(case proxy_lv when 1 then '普通玩家' when 2 then '金豆玩家' when 3 then '初级推广员' when 4 then '中级推广员' else '高级推广员' end) as whether")
                    ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                    ->where($where)
                    ->group("profit_log.gen_uid")
                    ->select();
                $json = $this->json_encode($rowss);
                echo $json;
            }
            /*$arr = json_decode($uids);
            $count = count($rowu);
            $str = "";
            for($i=0;$i<$count;$i++){
                $str .= "gen_uid=".$rowu[$i]['uid']." or ";
            }
            $where = substr($str,0,-4);
            $rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where($where)
                ->group("profit_log.gen_uid")
                ->select();
            $json = $this->json_encode($rowss);
            echo $json;*/
        }
        else {
            /*$rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where("gen_uid='$uidInput'")
                ->group("profit_log.gen_uid")
                ->select();*/
            $rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time,user_list.examine,(case proxy_lv when 1 then '普通玩家' when 2 then '金豆玩家' when 3 then '初级推广员' when 4 then '中级推广员' else '高级推广员' end) as whether")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where("gen_uid='$uidInput'")
                ->group("profit_log.gen_uid")
                ->select();
            $json = $this->json_encode($rowss);
            echo $json;
        }

        /*$row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("user_list")
            ->field("next_uids")
            ->where("uid='$uid' and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5')")
            ->select();
        //查询出来的数据只有一个
        if(empty($rows)){
            //$str = "";
            echo $this->json_encode("");
        }
        else {
            $uids = $rows[0]['next_uids'];
            $arr = json_decode($uids);
            $count = count($arr);
            $str = "";
            for($i=0;$i<$count;$i++){
                $str .= "gen_uid=".$arr[$i]." or ";
            }
            $where = substr($str,0,-4);
            $rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time,user_list.examine,(case proxy_lv when 1 then '普通玩家' when 2 then '金豆玩家' when 3 then '初级推广员' when 4 then '中级推广员' else '高级推广员' end) as whether")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where($where)
                ->group("profit_log.gen_uid")
                ->select();
            $json = $this->json_encode($rowss);
            echo $json;
        }*/
    }

    //得到待审核的数据信息
    public function beAuditedData(){
        $phone = I('post.phone');
        $uidInput = I('post.uid');
        //$uidInput = "100043";
        if(empty($uidInput)){
            $row = M("user_list")->where("phone_number='13200231520'")->select();
            $uid = $row[0]['uid'];
            $rows = M("user_list")
                ->field("next_uids")
                ->where("uid='$uid'")
                ->select();
            $uids = $rows[0]['next_uids'];
            $uids = substr($uids,0,-1);
            $uids = substr($uids,1);
            $rowu = M("user_list")
                    ->field("uid")
                    ->where("uid in ($uids) and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5') and examine=0")
                    ->select();
            //echo "<pre>";var_dump($rowu);
            if (empty($rowu)){
                echo $this->json_encode("");
            }
            else {
                echo $this->json_encode($rowu);
            }
        }
        else {
            $rowu = M("user_list")
                ->field("uid")
                ->where("uid in ($uidInput) and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5') and examine=0")
                ->select();
            echo $this->json_encode($rowu);
        }
    }

    //拒绝按钮的操作 2
    public function refuse(){
        $phone = I('post.phone');
        $uid = I('post.uidddd');
        $data['examine'] = "2";
        $row = M("user_list")->where("uid='$uid'")->save($data);
        if($row=true){
            $array['status']=1;
        }
        else {
            $array['status']=-1;
        }
        echo json_encode($array);
    }

    //同意按钮的操作 1
    public function agree(){
        $phone = I('post.phone');
        $uid = I('post.uidddd');
        $data['examine'] = "1";
        $row = M("user_list")->where("uid='$uid'")->save($data);
        if($row=true){
            $array['status']=1;
        }
        else {
            $array['status']=-1;
        }
        echo json_encode($array);
    }

}