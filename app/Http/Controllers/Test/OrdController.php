<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdController extends Controller
{
    public function encryption()
    {
        $n = 'litingzhangfei';
        $b=strlen($n);
        $pass = '';
        for($i=0;$i<$b;$i++)
        {
            // echo $n[$i].'>>>>>>>>>>>'.ord($n[$i]);echo '</br>';
            $ord = ord($n[$i])+4;
            $nn=chr($ord);
            // echo $ord;echo '</br>';
            $pass .= $nn;
        }
        echo $pass;
    }
    /**
     * 解密
     */
    public function decode()
    {
        $m = 'pmxmrk~lerkjim';
        $l = strlen($m);
        $pass = '';
        for($i=0;$i<$l;$i++)
        {
            // echo $m[$i];echo '</br>';echo '<hr>';
            $ord = ord($m[$i])-4;
            // echo $ord;echo '</br>';
            $k = chr($ord);
            echo $k;echo '</br>';
            $pass .= $k;
        }
        echo $pass;
    }
}
