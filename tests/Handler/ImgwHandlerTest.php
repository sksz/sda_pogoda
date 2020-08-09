<?php

namespace App\Test\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Mesurement;
use App\Handler\ImgwHandler;
use GuzzleHttp\Client;
use \Psr\Log\NullLogger;

class ImgwHandlerTest extends TestCase
{
    public $imgwClientMock;

    public $nullLogger;

    public function setUp()
    {
        $this->imgwClientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->nullLogger = new NullLogger();
    }

    public function dataNoPolishCharacters()
    {
        return [
            'Zestaw pierwszy' => [
                'city' => 'Szczecin',
                'expected' => 'szczecin',
            ],
            'Zestaw drugi' => [
                'city' => 'Kraków',
                'expected' => 'krakow',
            ],
        ];
    }

    /**
     * @dataProvider dataNoPolishCharacters
     */
    public function testNoPolishCharacters(string $city, string $expected)
    {
        $imgwHandler = new \ReflectionClass(ImgwHandler::class);
        $imgwMethod = $imgwHandler->getMethod('noPolishCharacters');
        $imgwMethod->setAccessible(true);

        $this->assertEquals(
            $expected,
            $imgwMethod->invokeArgs(
                new ImgwHandler(
                    $this->imgwClientMock,
                    $this->nullLogger
                ),
                [$city]
            ),
            'Test usunięcia polskich znaków zakończył się błędem.'
        );
    }
}
