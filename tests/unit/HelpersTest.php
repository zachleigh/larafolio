<?php

namespace Larafolio\tests\unit;

use Larafolio\tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @test
     */
    public function cache_bust_correctly_resolves_css_filename()
    {
        $data = json_encode([
            "css/larafolio-final.css" => "css/larafolio-final-fe2dbb5504.css",
            "js/larafolio.js" => "js/larafolio-d54b988e06.js"
        ]);

        $filename = manager_cache_bust(
            'vendor/larafolio/css/larafolio-final.css',
            $data
        );

        $this->assertEquals(
            '/vendor/larafolio/css/larafolio-final-fe2dbb5504.css',
            $filename
        );
    }

    /**
     * @test
     */
    public function cache_bust_correctly_resolves_js_filename()
    {
        $data = json_encode([
            "css/larafolio-final.css" => "css/larafolio-final-fe2dbb5504.css",
            "js/larafolio.js" => "js/larafolio-d54b988e06.js"
        ]);

        $filename = manager_cache_bust(
            'vendor/larafolio/js/larafolio.js',
            $data
        );

        $this->assertEquals(
            '/vendor/larafolio/js/larafolio-d54b988e06.js',
            $filename
        );
    }
}