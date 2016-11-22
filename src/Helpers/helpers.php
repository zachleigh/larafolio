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
