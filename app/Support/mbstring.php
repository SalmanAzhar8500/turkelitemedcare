<?php

if (! function_exists('mb_split')) {
    /**
     * Lightweight fallback for PHP builds without ext-mbstring.
     */
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        $delimiter = '~';
        $escapedPattern = str_replace($delimiter, '\\'.$delimiter, $pattern);
        $result = preg_split($delimiter.$escapedPattern.$delimiter.'u', $string, $limit);

        return $result === false ? false : $result;
    }
}

if (! function_exists('mb_strimwidth')) {
    /**
     * Minimal fallback for CLI tools when ext-mbstring is unavailable.
     */
    function mb_strimwidth(string $string, int $start, int $width, string $trim_marker = '', ?string $encoding = null): string
    {
        $chars = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        if ($chars === false) {
            $chars = str_split($string);
        }

        $slice = array_slice($chars, max(0, $start), max(0, $width));

        return implode('', $slice).($trim_marker !== '' && count($chars) > ($start + $width) ? $trim_marker : '');
    }
}

if (! function_exists('mb_strwidth')) {
    function mb_strwidth(string $string, ?string $encoding = null): int
    {
        $chars = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        return $chars === false ? strlen($string) : count($chars);
    }
}
