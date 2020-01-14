<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class Regs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty($_SERVER['HTTP_TOKEN']) || empty($_SERVER['HTTP_NAME'])){
                $response = [
                    'errno' =>'40005',
                    'msg'   => 'empty error!!',
                ];
                return \json_encode($response,true);
        }
        $user_token = $_SERVER['HTTP_TOKEN'];
        //echo 'user_token:'.$user_token;
        //echo '<br>';
        $name = $_SERVER['HTTP_NAME'];
        //echo $name;
        $pri='abcdest'; 
        $key=md5($name.$pri);
        $redis_key = 'str:dss:u:'.$key;
       // echo 'redis key:'.$redis_key;echo '<br>';
        $count = Redis::get($redis_key);      //获取接口的访问次数
       // echo '接口的访问次数：'.$count;
        if ($count >=5) {
            echo '太频繁了，请稍后操作';
            Redis::expire($redis_key, 10);
            die;
        }
        $count = Redis::incr($redis_key);
        //echo 'count:'.$count;
        return $next($request);
    }
}
