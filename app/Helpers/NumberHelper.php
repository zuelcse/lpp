<?php

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $fmt = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        return ucfirst($fmt->format($number));
    }
}

if (!function_exists('formatIndianCurrency')) {
    function formatIndianCurrency($number, $parenthesesForNegative = false)
    {
        $isNegative = $number < 0;
        $abs = abs((float)$number);
        $parts = explode('.', sprintf('%.2f', $abs));
        $intPart = $parts[0];
        $decPart = $parts[1];

        if (strlen($intPart) > 3) {
            $lastThree = substr($intPart, -3);
            $rest = substr($intPart, 0, -3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $formatted = $rest . ',' . $lastThree;
        } else {
            $formatted = $intPart;
        }

        $res = $formatted . '.' . $decPart;
        if ($isNegative) {
            return $parenthesesForNegative ? '(    ' . $res . ')' : '-' . $res;
        }
        return $res;
    }
}
