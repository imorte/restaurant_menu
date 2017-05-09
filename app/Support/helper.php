<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 08.05.2017
 * Time: 20:25
 */

use Carbon\Carbon;

/**
 * @param $date
 * @return mixed
 */
function ts($date)
{
    return Carbon::parse($date)->timestamp;
}
