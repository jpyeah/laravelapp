<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{   
    protected $table = 'coupon';
     public $timestamps = false;
    /**
     * 查询友情链接
     */
    public function getlist()
    {
        return DB::table('coupon')->get();
    }
}
