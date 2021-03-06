<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PubModel extends Model
{
    public $primaryKey='id';
    protected $table='a_pubkey';
    public $timestamps=false;
 
    //白名单  表设计中不允许为空的
    // protected $fillable = ['cate_name'];
    //黑名单   表设计中允许为空的
    protected $guarded = [];
}
