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
    function manager_cache_bust($path, $jsonData = null, $root = 'vendor/larafolio/')
    {
        $path = trim($path, '/');

        $file = str_replace($root, '', $path);

        if (!$jsonData && file_exists(public_path($root.'rev-manifest.json'))) {
            $jsonData = file_get_contents(public_path($root.'rev-manifest.json'));
        }

        if ($jsonData) {
            $data = json_decode($jsonData, true);

            if (array_key_exists($file, $data)) {
                return '/'.$root.$data[$file];
            }

            return elixir($path);
        }

        abort(500, "Resource {$path} could not be resolved.");
    }
}
