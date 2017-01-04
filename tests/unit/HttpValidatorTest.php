<?php

namespace Larafolio\tests\unit;

use Larafolio\tests\TestCase;

class HttpValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_200_status_code()
    {
        $httpValidator = app()->make('Larafolio\Http\HttpValidator\HttpValidator');

        $httpCode = $httpValidator->validate('http://httpstat.us/200');

        $this->assertEquals(200, $httpCode);
    }

    /**
     * @test
     */
    public function it_gets_300_range_status_codes()
    {
        $httpValidator = app()->make('Larafolio\Http\HttpValidator\HttpValidator');

        $httpCode = $httpValidator->validate('http://httpstat.us/302');

        $this->assertEquals(302, $httpCode);
    }

    /**
     * @test
     */
    public function it_gets_400_range_status_codes()
    {
        $httpValidator = app()->make('Larafolio\Http\HttpValidator\HttpValidator');

        $httpCode = $httpValidator->validate('http://httpstat.us/404');

        $this->assertEquals(404, $httpCode);
    }

    /**
     * @test
     */
    public function it_gets_500_range_status_codes()
    {
        $httpValidator = app()->make('Larafolio\Http\HttpValidator\HttpValidator');

        $httpCode = $httpValidator->validate('http://httpstat.us/500');

        $this->assertEquals(500, $httpCode);
    }
}
