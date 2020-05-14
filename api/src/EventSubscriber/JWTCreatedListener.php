<?php


namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['user_id'] = $user->getId();
        $payload['email'] = $user->getEmail();

        $event->setData($payload);
    }
}
