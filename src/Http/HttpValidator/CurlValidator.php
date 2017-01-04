<?php

namespace Larafolio\Http\HttpValidator;

class CurlValidator implements HttpValidator
{
    /**
     * Validate the given url.
     *
     * @param string $url The url to validate.
     *
     * @return int|bool httpCode
     */
    public function validate($url)
    {
        $handle = curl_init($url);

        if ($handle) {
            $this->setCurlOptions($handle);

            $response = curl_exec($handle);

            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            curl_close($handle);

            return $httpCode;
        }

        return false;
    }

    /**
     * Set curl options.
     *
     * @param resource $handle Curl handle.
     */
    protected function setCurlOptions($handle)
    {
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, true);

        curl_setopt($handle, CURLOPT_NOBODY, true);

        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);

        curl_setopt($handle, CURLOPT_TIMEOUT, 10);
    }
}
