<?php

namespace App\Test\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Contact;

class ContactTest extends TestCase
{
    public function testCanSetReplyTo()
    {
        $contact = new Contact();
        $contact->setReplyTo('mail@testowy.pl');

        $this->assertEquals(
            'mail@testowy.pl',
            $contact->getReplyTo(),
            'Test ustawienia pola `replyTo` w encji `Contact` zakończył się niepowodzeniem.'
        );
    }

    public function testCanSetContent()
    {
        $contact = new Contact();
        $contact->setContent('Treść wiadomości kontaktowej');

        $this->assertSame(
            'Treść wiadomości kontaktowej',
            $contact->getContent(),
            'Test ustawienia pola `content` w encji `Contact` zakończył się niepowodzeniem.'
        );
    }

    public function testSetName()
    {
        $contact = new Contact();
        $contact->setName('Jan Niedźwiedź');

        $this->assertSame(
            'Jan Niedźwiedź',
            $contact->getName(),
            'Test ustawienia pola `name` w encji `Contact` zakończył się niepowodzeniem.'
        );
    }

    public function testSetTimestamp()
    {
        $dateTime = new \DateTime('NOW');
        $contact = new Contact();
        $contact->setTimestamp($dateTime);

        $this->assertSame(
            $dateTime,
            $contact->getTimestamp(),
            'Test ustawienia pola `timestamp` w encji `Contact` zakończył się niepowodzeniem.'
        );
    }
}
