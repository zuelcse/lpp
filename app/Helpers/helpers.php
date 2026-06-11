<?php

if (!function_exists('formatAmount')) {
    function formatAmount($amount, $decimals = 2)
    {
        return number_format((float)$amount, $decimals, '.', ',');
    }
}

if (!function_exists('greeting')) {
    function greeting($name)
    {
        return "Hello, {$name}!";
    }
}



