<?php

namespace App\Handler;

use App\Entity\Notification;
use App\Message\UserCreated;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserCreatedHandler
{
    public function __construct(
        protected LoggerInterface        $logger,
        protected EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        $notification = new Notification();
        $notification->setPayload((array)$userCreated);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        $this->logger->warning('NEW USER LOGGED: ' . $userCreated->getFirstName() . ' ' . $userCreated->getEmail());
    }
}