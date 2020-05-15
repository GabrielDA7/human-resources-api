<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Offer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OfferSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $storage;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'create', EventPriorities::PRE_WRITE
            ],
        ];
    }

    public function create(ViewEvent $event) {
        $offer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$offer instanceof  Offer || Request::METHOD_POST !== $method)
            return;
        $user = $this->storage->getToken()->getUser();
        $offer->setOwner($user);
    }
}
