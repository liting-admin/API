<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\PubModel as Wd;

class JieController extends Controller
{
    public function index()
    {
        $a = $_GET['str'];
        $f = strlen($a);
        
        $pass = '';
        for ($i=0;$i<$f;$i++) {
            $ord = ord($a[$i])+5;
            $chr = chr($ord);
            $pass .= $chr;
        }
        echo $pass;
    }

    public function dds()
    {
        $a = $_GET['str'];
        $f = strlen($a);
        
        $pass = '';
        for ($i=0;$i<$f;$i++) {
            $ord = ord($a[$i])-5;
            $chr = chr($ord);
            $pass .= $chr;
        }
        echo $pass;
    }

    public function addkey()
    {
        return view('user.addkey');
    }
    public function deckey()
    {
        return view('user.deckey');
    }

    public function addkeys()
    {
        $key = trim($_POST['sshkey']);
        $uid = Auth::id();
        $user_name = Auth::user()->name;
        
        $data = [
            'uid' => $uid,
            'name' => $user_name,
            'pubkey' => trim($key)
        ];

        $f = Wd::where('uid', $uid)->first();
        if ($f) {
            echo '<span style=color:red>提示：数据已存在！</span>';
            echo '</br>';
            echo '<hr>';

            echo '<span style=color:red>pubkey----->:</span>' . $key;
            echo '</br>';
            echo '<hr>';
            // echo '<span style=color:red>base64----->:</span>';echo '</br>';echo '<hr>';

            header('refresh:7,url=' . env('APP_URL') . '/home');
            echo '<span style=color:red>正在跳转页面 稍等.......</span>';
        } else {
            $res = Wd::insertGetId($data);
            if ($res) {
                header('refresh:3,url=' . env('APP_URL') . '/home');
                echo "添加成功 公钥内容：" . $key;
                echo '</br>';
                echo '<span style=color:red>正在跳转页面 稍等.......</span>';
            }
        }
    }
    public function deckeys()
    {
        $enc_data = trim($_POST['enc_data']);
        
        //解密
        $uid = Auth::id();
        $u = Wd::where('uid', $uid)->value('pubkey');
        $a=base64_decode($enc_data);
        openssl_public_decrypt($a,$dec,$u);
        echo '<span style=color:red>解密数据：</span>'.$dec;
    }
    //接收
    public function sign()
    {
       print_r($_GET);
       echo '</br>';
       echo '<hr>';
       $sign=$_GET['sign'];
        unset($_GET['sign']);
        print_r($sign);
        ksort($_GET);
        $str = '';
        foreach($_GET as $k => $v)
        {
            $str .= $k .'=' .$v.'&';
        } 
        //去掉&符号
        $str=rtrim($str,'&');
        echo $str;
        //使用公钥
        echo '<hr>';
        $pubkey = file_get_contents(storage_path('keys/pub.key'));
        $status = openssl_verify($str,base64_decode($sign),$pubkey,OPENSSL_ALGO_SHA256);
        print_r($status);
        // echo 111;

    }

    public function liting()
    {
        return view('user.insert');
    }
    

    public function zhangfei()
    {
        unset($_POST['_token']);
        print_r($_POST);echo '</br>';echo '<hr>';
        $sign = base64_decode($_POST['sign']);
        unset($_POST['sign']);
        print_r($_POST);echo '</br>';echo '<hr>';
        $params =[];
        foreach($_POST['k'] as $k=>$v)
        {
            if(empty($v)){
                continue;     //跳过空字段

            }
            $params[$v] = $_POST['v'][$k];
           
        }
        // print_r($params[$v]);
        ksort($params);
        print_r($params);echo '</br>';echo '<hr>';
        //拼接参数
        $str = "";
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str = trim($str,'&');

        //验签
        $uid = Auth::id();
        $u =Wd::where('uid',$uid)->value('pubkey');

        $status = openssl_verify($str,$sign,$u,OPENSSL_ALGO_SHA256);
        if($status)
        {
            echo '<h1 style=color:red>验签ok</h1>';
        }else{
            echo '验签失败';
        }
    }

}