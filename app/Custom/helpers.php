<?php

if (!function_exists('result')) {
    function result($data)
    {
        return ['message' => 'Data Successfully Find', 'data' => $data];
    }
}
