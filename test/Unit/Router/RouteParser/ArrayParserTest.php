<?php

namespace PitchBladeTest\Router\RouteParser;

use PitchBlade\Router\RouteParser\ArrayParser,
    PitchBladeTest\Mocks\Router\Routes;

class ArrayParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PitchBlade\Router\RouteParser\ArrayParser::__construct
     */
    public function testConstructCorrectInterface()
    {
        $parser = new ArrayParser(new Routes());

        $this->assertInstanceOf('\\PitchBlade\\Router\\RouteParser\\Parser', $parser);
    }

    /**
     * @covers PitchBlade\Router\RouteParser\ArrayParser::__construct
     * @covers PitchBlade\Router\RouteParser\ArrayParser::parse
     */
    public function testParseWithoutMapping()
    {
        $parser = new ArrayParser(new Routes());

        $routes = [
            'with mapping' => [
                'requirements' => [],
                'view' => null,
                'controller' => [],
            ],
        ];

        $this->assertNull($parser->parse($routes));
    }

    /**
     * @covers PitchBlade\Router\RouteParser\ArrayParser::__construct
     * @covers PitchBlade\Router\RouteParser\ArrayParser::parse
     */
    public function testParseWithMapping()
    {
        $parser = new ArrayParser(new Routes());

        $routes = [
            'with mapping' => [
                'requirements' => [],
                'view' => null,
                'controller' => [],
                'mapping' => [],
            ],
        ];

        $this->assertNull($parser->parse($routes));
    }
}