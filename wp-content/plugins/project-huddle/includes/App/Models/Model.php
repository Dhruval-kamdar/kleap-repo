<?php

namespace PH\Models;


if (!defined('ABSPATH')) {
    exit;
}

abstract class Model implements \PH\Contracts\Model
{
    public static function get($id = 0)
    {
        return new static($id);
    }
}
