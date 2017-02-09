<?php

namespace Larafolio\Http;

class CacheBuster
{
    /**
     * Resolve path for versioned resource file.
     *
     * @param string $path     Unversioned file path.
     * @param string $jsonData Json object.
     * @param string $root     Location of files in /public.
     *
     * @return string
     */
    public function resolvePath($path, $jsonData, $root)
    {
        $file = trim(str_replace($root, '', $path), '/');

        $jsonData = $this->validateJsonData($path, $jsonData, $root);

        $data = json_decode($jsonData, true);

        if (array_key_exists($file, $data)) {
            return '/'.$root.'/'.$data[$file];
        }

        return elixir($path);
    }

    /**
     * Get json data from rev manifest and check for null.
     * 
     * @param string $path     Unversioned file path.
     * @param string $jsonData Json object.
     * @param string $root     Location of files in /public.
     *
     * @return string
     */
    protected function validateJsonData($path, $jsonData, $root)
    {
        if ($jsonData === null && file_exists(public_path($root.'/rev-manifest.json'))) {
            $jsonData = file_get_contents(public_path($root.'/rev-manifest.json'));
        }

        if ($jsonData === null) {
            abort(500, "Resource {$path} could not be resolved.");
        }

        return $jsonData;
    }
}
