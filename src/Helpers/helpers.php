<?php

if (!function_exists('manager_flash')) {
    /**
     * Flash a message to the session.
     *
     * @param string $title   Flash message title.
     * @param string $message Flash message body.
     *
     * @return mixed
     */
    function manager_flash($title = null, $message = null)
    {
        $flash = app('Larafolio\Http\Flash');

        if (func_num_args() == 0) {
            return $flash;
        }

        return $flash->message($title, $message);
    }
}

if (!function_exists('manager_cache_bust')) {
    /**
     * Return the versioned asset file for given file.
     *
     * @param string $path     Unversioned file path.
     * @param string $jsonData Json object.
     * @param string $root     Location of files in /public.
     *
     * @return string
     */
    function manager_cache_bust($path, $jsonData = null, $root = 'vendor/larafolio')
    {
        $path = trim($path, '/');

        $root = trim($root, '/');

        $cacheBuster = app('Larafolio\Http\CacheBuster');

        return $cacheBuster->resolvePath($path, $jsonData, $root);
    }
}
