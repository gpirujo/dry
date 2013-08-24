<?php

# case insensitive array_merge
function array_merge_ci() {
    $result = array();
    $keys = array();
    foreach (func_get_args() as $array) {
        foreach ($array as $key => $value) {
            $result[check_set($keys[strtolower($key)], $key)] = $value;
        }
    }
    return $result;
}
