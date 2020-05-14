<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private TokenGeneratorInterface $tokenGenerator;
    private Swift_Mailer $mailer;
    private RequestStack $request;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator, Swift_Mailer $mailer, RequestStack $request)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['registration', EventPriorities::PRE_WRITE],
                ['sendEmailConfirmation', EventPriorities::POST_WRITE]
            ],


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
        $user->setConfirmationToken($this->tokenGenerator->generateToken());
    }

    public function sendEmailConfirmation(ViewEvent $event) : void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }
        if (in_array("ROLE_ADMIN", $user->getRoles()))
            throw new BadRequestHttpException();

        $message = (new Swift_Message('Account confirmation'))
            ->setFrom('hr@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(sprintf('Confirm token : %s', $user->getConfirmationToken()));
        $this->mailer->send($message);
    }

    private function encodePassword($user) {
        $passwordEncoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($passwordEncoded);
    }
}
