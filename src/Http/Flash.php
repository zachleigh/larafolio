<?php

namespace Larafolio\Http;

class Flash
{
    /**
     * Flash a standard message.
     *
     * @param string $title
     * @param string $message
     */
    public function message($title, $message)
    {
        session()->flash('flash_message', [
            'title' => $title,
            'message' => $message,
            'type' => 'success',
        ]);
    }

    /**
     * Flash an error message.
     *
     * @param string $title
     * @param string $message
     */
    public function error($title, $message)
    {
        session()->flash('flash_message', [
            'title' => $title,
            'message' => $message,
            'type' => 'error',
        ]);
    }

    /**
     * Flash an info message.
     *
     * @param string $title
     * @param string $message
     */
    public function info($title, $message)
    {
        session()->flash('flash_message', [
            'title' => $title,
            'message' => $message,
            'type' => 'info',
        ]);
    }
}
