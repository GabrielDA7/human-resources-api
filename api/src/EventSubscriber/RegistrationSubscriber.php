<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private TokenGeneratorInterface $tokenGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['registration', EventPriorities::PRE_WRITE],
        ];
    }

    public function registration(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $this->encodePassword($user);
        $this->sendEmailConfirmation($user);
    }

    public function sendEmailConfirmation($user) {
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
        //send email
    }

    public function encodePassword($user) {
        $passwordEncoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($passwordEncoded);
    }
}
