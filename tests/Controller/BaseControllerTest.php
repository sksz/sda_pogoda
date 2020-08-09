<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testContact()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
