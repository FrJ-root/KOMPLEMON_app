<?php

if (!function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  string|array|null  $key
     * @param  mixed  $default
     * @return mixed|\App\Services\Settings
     */
    function setting($key = null, $default = null)
    {
        $settings = app('settings');

        if (is_null($key)) {
            return $settings;
        }

        if (is_array($key)) {
            $settings->set($key);
            return $settings;
        }

        return $settings->get($key, $default);
    }
}
