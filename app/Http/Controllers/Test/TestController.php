<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\TestModel as Wd;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Model\RegModel as Ws;
class TestController extends Controller
{
    public function index()
    {
        $name = request()->input('name');
        $password1 = request()->input('password');
        $password2 = request()->input('passwords');
        $time = time();

        if($password1 != $password2){
            die('密码不一致哦');

        }
        $password =password_hash($password1, PASSWORD_BCRYPT);
        $passwordd =password_hash($password2, PASSWORD_BCRYPT);
        $data = [
            'name' => $name,
            'password' => $password,
            'logs_time' => $time,
            'passwords' => $passwordd,
            'user_ip' =>$_SERVER['REMOTE_ADDR']
        ];
    $res = Wd::insert($data);
    }
    public function user()
    {
        $name = request()->input('name');
        $password = request()->input('password');
        // echo $password;
        $res = Wd::where('name',$name)->first();
        if($res){
            $date=password_verify($password,$res->password);
            if($date==true){

                $token = Str::random(30);
                $response = [
                        'erron' => '0',
                        'msg'   => 'ok',
                        'data' => [
                            'token' => $token
                        ]
                ];
            }else{

                $response = [
                    'erron' => '40001',
                    'msg'   => '密码不正确哦'
                ];
            }
        }else{

            $response = [
                'erron' => '40003',
                'msg'   => '用户不存在哦'
            ];
        }
       return $response;
       
    }
    /**
     * Undocumented function
     * 获取用户列表
     * @return void
     */
    public function userList()
    {
        // print_r($_SERVER);echo '</br>';
        $user_token = $_SERVER['HTTP_TOKEN'];
        // echo 'user_token:'.$user_token;
        $current_url = $_SERVER['REQUEST_URI'];
        // echo "当前URL：".$current_url;

        $redis_key = 'str:count:u:'.$user_token.'url:'.md5($current_url);
        // echo 'redis key:'.$redis_key;;


        $count = Redis::get($redis_key);      //获取接口的访问次数
        // echo '接口的访问次数：'.$count;

        if($count >=5){
            echo '太频繁了，请稍后操作';
            Redis::expire($redis_key,3600);
            die;
        }

        $count = Redis::incr($redis_key);
        echo 'count:'.$count;
    }
/**
 * 
 * 
 * 注册
 *
 * 
 */
    public function api()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $password =$_POST['password'];
        $passwords = $_POST['passwords'];
        if(empty($name) && empty($email) && empty($tel) && empty($password) &&empty($passwords)){
            $response=[
                'errno' => '40006',
                'msg' => 'no empty!!'
            ];
            return json_encode($response,true);
        }
        if ($passwords != $password) {
            $response=[
                'errno' => '40001',
                'msg' => 'password error!'
            ];
            return $response;
        }
        $res = Ws::where('name', $name)->first();
        if ($res) {
            $response=[
                'errno' => '40002',
                'msg' => 'user name exist!!'
            ];
            return $response;
        }
        $res1 = Ws::where('tel', $tel)->first();
        if ($res1) {
            $response=[
                'errno' => '40003',
                'msg' => 'tel exist!!'
            ];
            return $response;
        }
        $res2 = Ws::where('email', $email)->first();
        if ($res2) {
            $response=[
                'errno' => '40004',
                'msg' => 'email exist!!'
            ];
            return $response;
        }
        $res4 = Ws::insert($_POST);
        if ($res4) {
            $response=[
                'errno' => '40005',
                'msg' => 'success!!'
            ];
            echo '注册成功';
            return json_encode($response,true);
        }
    }
    public function red()
    {
        if(empty($_SERVER['HTTP_TOKEN']) || empty($_SERVER['HTTP_NAME']) ){
            $response = [
                'errno'  => '40005',
                'msg'    => 'MANE OR TOKRN NOT Vailt!'
            ];
            return $response;
        }
        $user_token = $_SERVER['HTTP_TOKEN'];
        $name=$_SERVER['HTTP_NAME'];
       
        $url = "http://passport.litingstudio.top/auth";
        
        $data=[
            'user_token' . $user_token,
            'name'.$name
        ];
        //初始化
        $ch = curl_init();
        //设置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$data);
        //发起请求
        $res = curl_exec($ch);
        //关闭回话
        $response=curl_close($ch);
      
    }
    public function login()
    {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $res = Ws::where('name',$name)->where('password',$password)->first();
        if($res){
          
            $token = Str::random(30);
            $pri='abcdest'; 
            $key=md5($name.$pri);
            $redis_key = 'str:dss:u:'.$key;
            $f =  Redis::set($redis_key,$token);
            // echo $redis_key;
            $redis_time = Redis::expire($redis_key,86400*7);
            echo "<a href='token2' style=color:red>点击验证TOKEN</a>";
            echo '</br>';
            echo '登录成功';
           
            $rr = [
            'erron' => '0',
            'msg'   => 'ok',
            'data' => [
                'token' => $token,
           ]
        ];
            return json_encode($rr,true);
        }else{
            $response = [
                'name' => '40008',
                'msg'  => 'name or password error!!'
            ];
            echo '登录失败';
           return json_encode($response,true);
        }
        }
        public function qian(){
            $data = 'liting';
            $key = '1905';
            $signature= md5($data . $key);
            echo "待发送的数据：". $data;
            echo '</br>';
        //发送数据
        $url = "http://1905passport.com/test/ming?data=".$data . '&signature='.$signature;
        echo $url;
        echo '<hr>';

        $response = file_get_contents($url);
        echo $response;
        }
    
   public function postqian(){
       $key = '20010409';
       $data = [
           'order_id'   => 'L_N' . mt_rand(11111,99999),
           'order_amount' => mt_rand(111,999),
           'u_id'   => 12,
           'time'   =>time(),
       ];
       $json = json_encode($data);
       $sign = md5($json . $key);
       $url = "http://1905passport.com/postming";
        
       $data=[
           'sign' => $sign,
           'data'=>$json
       ];
         //初始化
         $ch = curl_init();
         //设置参数
         curl_setopt($ch,CURLOPT_URL,$url);
         curl_setopt($ch,CURLOPT_POST,1);
         curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
         //发起请求
         curl_exec($ch);
         //关闭回话
         curl_close($ch);
 
     

   }
   //私钥验签
   public function priv(){
    $data = [
        'name' => 'liting',
        'age'  => '女' 
    ];
    ksort($data);
    //拼接呆签名字符串
    $str = '';
    foreach($data as $k => $v)
    {
        $str .= $k .'=' .$v.'&';
    } 
     //去掉&符号
     $str=rtrim($str,'&');
     //使用私钥进行签名
     $privkey = file_get_contents(storage_path('keys/priv'));
     openssl_sign($str,$signature,$privkey,OPENSSL_ALGO_SHA256);
     echo $signature;echo '</br>';echo '<hr>';
     //base64_encode
     $sign = base64_encode($signature);
     echo $sign;echo '</br>';echo '<hr>';
    $url = "http://1905passport.com/priv";
   
    $data=[
        'sign' => $sign,
        'data'=>$str
    ];
      //初始化
      $ch = curl_init();
      //设置参数
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
      //发起请求
      curl_exec($ch);
      //关闭回话
      curl_close($ch);


   }
   //加密
   public function enc()
    {
        $data = 'helloworld';
        $json=json_encode($data);echo '</br>';
        echo $json;
        echo '<hr>';
        $method = 'AES-256-CBC';
        $key = '1905API';
        $VI = 'FJJFNC4567BMjhgf';

        $enc_data = openssl_encrypt($json,$method,$key,OPENSSL_RAW_DATA,$VI);
        // $json = base64_encode($enc_data);
        echo "加密------>". $enc_data;echo '</br>';
        echo '<hr>';
        $url = "http://1905passport.com/dec?data=".urlencode(base64_encode($enc_data));
        echo $url;echo '</br>';
        echo '<hr>';
        $response = file_get_contents($url);
        echo $response;
    
   }
   //非对称加密
   public function feienc(){
       $priv_key=file_get_contents(storage_path('keys/priv'));
       echo $priv_key;echo '<hr>';
       $data = 'helloworld';
       openssl_private_encrypt($data,$enc_data,$priv_key);
       echo 'encrypt:';print_r($enc_data);echo '<hr>';
       $base64_str = base64_encode($enc_data);
       echo 'base64_encode:'.$base64_str;echo '<hr>';
       $urlencode = urlencode($base64_str);
       echo 'urlencode:'.$urlencode;echo '<hr>';
       $url = "http://1905passport.com/feidec?data=". $urlencode;
       echo 'url:'.$url;echo '<hr>';
    //    $response = file_get_contents($url);
    //    echo $response;




   }
   
}
