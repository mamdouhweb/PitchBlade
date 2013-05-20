<?php

namespace PitchBladeTest\Unit\Router;

use PitchBlade\Router\RouteFactory,
    PitchBladeTest\Mocks\Router\RequestMatcher,
    PitchBlade\Router\Route;

class RouteFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PitchBlade\Router\RouteFactory::__construct
     */
    public function testConstructCorrectInstance()
    {
        $factory = new RouteFactory(new RequestMatcher());

        $this->assertInstanceOf('\\PitchBlade\\Router\\RouteBuilder', $factory);
    }

    /**
     * @covers PitchBlade\Router\RouteFactory::__construct
     * @covers PitchBlade\Router\RouteFactory::build
     */
    public function testBuildWithoutMapping()
    {
        $factory = new RouteFactory(new RequestMatcher());

        $this->assertInstanceOf(
            '\\PitchBlade\\Router\\Route',
            $factory->build('test', [], 'view', ['controller', 'action'])
        );
    }

    /**
     * @covers PitchBlade\Router\RouteFactory::__construct
     * @covers PitchBlade\Router\RouteFactory::build
     */
    public function testBuildWithMapping()
    {
        $factory = new RouteFactory(new RequestMatcher());

        $this->assertInstanceOf(
            '\\PitchBlade\\Router\\Route',
            $factory->build('test', [], 'view', ['controller', 'action'], [])
        );
    }
}