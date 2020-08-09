<?php

namespace App\Test\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Mesurement;

class MesurementTest extends TestCase
{
    public function testCanSetCity()
    {
        $mesurement = new Mesurement();
        $mesurement->setCity('Szczecin');

        $this->assertEquals(
            'Szczecin',
            $mesurement->getCity(),
            'Test ustawienia pola `city` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetTemperature()
    {
        $mesurement = new Mesurement();
        $mesurement->setTemperature(24.1);

        $this->assertEquals(
            24.1,
            $mesurement->getTemperature(),
            'Test ustawienia pola `temperature` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetWindSpeed()
    {
        $mesurement = new Mesurement();
        $mesurement->setWindSpeed(24.1);

        $this->assertEquals(
            24.1,
            $mesurement->getWindSpeed(),
            'Test ustawienia pola `windSpeed` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetWindDirection()
    {
        $mesurement = new Mesurement();
        $mesurement->setWindDirection(22);

        $this->assertEquals(
            22,
            $mesurement->getWindDirection(),
            'Test ustawienia pola `windDirection` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetPressure()
    {
        $mesurement = new Mesurement();
        $mesurement->setPressure(22.6);

        $this->assertEquals(
            22.6,
            $mesurement->getPressure(),
            'Test ustawienia pola `pressure` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetStationId()
    {
        $mesurement = new Mesurement();
        $mesurement->setStationId(1233);

        $this->assertEquals(
            1233,
            $mesurement->getStationId(),
            'Test ustawienia pola `stationId` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetRain()
    {
        $mesurement = new Mesurement();
        $mesurement->setRain(11);

        $this->assertEquals(
            11,
            $mesurement->getRain(),
            'Test ustawienia pola `rain` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetHumidity()
    {
        $mesurement = new Mesurement();
        $mesurement->setHumidity(14.5);

        $this->assertEquals(
            14.5,
            $mesurement->getHumidity(),
            'Test ustawienia pola `humidity` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }

    public function testCanFromRowResponse()
    {
        $data = [
            'temperatura' => 12.2,
            'predkosc_wiatru' => 22.5,
            'kierunek_wiatru' => 12,
            'cisnienie' => 1330.1,
            'id_stacji' => 12233,
            'suma_opadu' => 2,
            'wilgotnosc_wzgledna' => 33.2,
        ];
        $city = 'Szczecin';

        $mesurement = new Mesurement();
        $mesurement->fromRowResponse($data, $city);

        $this->assertEquals(
            12.2,
            $mesurement->getTemperature(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `temperature`.'
        );

        $this->assertEquals(
            22.5,
            $mesurement->getWindSpeed(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `windSpeed`.'
        );

        $this->assertEquals(
            12,
            $mesurement->getWindDirection(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `windDirection`.'
        );

        $this->assertEquals(
            1330.1,
            $mesurement->getPressure(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `pressure`.'
        );

        $this->assertEquals(
            1330.1,
            $mesurement->getPressure(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `pressure`.'
        );

        $this->assertEquals(
            12233,
            $mesurement->getStationId(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `stationId`.'
        );

        $this->assertEquals(
            2,
            $mesurement->getRain(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `rain`.'
        );

        $this->assertEquals(
            33.2,
            $mesurement->getHumidity(),
            'Test ustawienia encji `Mesurement` z tablicy zakończył się niepowodzeniem dla pola `humidity`.'
        );
    }

    public function testCanSetTimestamp()
    {
        $dateTime = new \DateTime('NOW');

        $mesurement = new Mesurement();
        $mesurement->setTimestamp($dateTime);

        $this->assertEquals(
            $dateTime,
            $mesurement->getTimestamp(),
            'Test ustawienia pola `timestamp` w encji `Mesurement` zakończył się niepowodzeniem.'
        );

        $mesurement = new Mesurement();
        $mesurement->setTimestamp();

        $this->assertInstanceOf(
            \DateTime::class,
            $mesurement->getTimestamp(),
            'Test automatycznego ustawienia pola `timestamp` w encji `Mesurement` zakończył się niepowodzeniem.'
        );
    }
}
