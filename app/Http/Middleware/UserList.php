<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class UserList
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
        $user_token = $_SERVER['HTTP_TOKEN'];
        echo 'user_token:'.$user_token;
        echo '<br>';
        $current_url = $_SERVER['REQUEST_URI'];
        echo "当前URL：".$current_url;echo '<hr>';
        $redis_key = 'str:count:u:'.$user_token.'url:'.md5($current_url);
        echo 'redis key:'.$redis_key;echo '<br>';
        $count = Redis::get($redis_key);      //获取接口的访问次数
        echo '接口的访问次数：'.$count;
        if ($count >=5) {
            echo '太频繁了，请稍后操作';
            Redis::expire($redis_key, 10);
            die;
        }
        // $count = Redis::incr($redis_key);
        // echo 'count:'.$count;
        return $next($request);
    }
    
}
