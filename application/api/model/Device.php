<?php
/**
 * Created by PhpStorm.
 * Author: Floating dust
 * Date: 2018-04-29
 * Time: 23:00
 */

namespace app\api\model;


use think\Model;

class Device extends Model
{
    protected $autoWriteTimestamp = false;
    protected $table='kami_data';
}