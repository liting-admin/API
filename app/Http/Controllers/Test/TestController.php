<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\TestModel as Wd;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
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
}
