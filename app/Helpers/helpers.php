<?php

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return setting('currency_symbol', '$');
    }
}

if (! function_exists('money')) {
    function money($amount, int $decimals = null): string
    {
        $decimals = $decimals ?? (int) setting('decimal_places', 2);
        $symbol = currency_symbol();
        $position = setting('currency_position', 'before');

        $formatted = number_format((float) $amount, $decimals);

        return $position === 'before'
            ? $symbol . ' ' . $formatted
            : $formatted . ' ' . $symbol;
    }
}