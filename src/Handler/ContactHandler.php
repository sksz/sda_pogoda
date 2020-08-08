<?php

namespace App\Handler;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;

class ContactHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function newContact(Contact $contact)
    {
        $contact->setTimestamp(new \DateTime('NOW'));
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }
}
