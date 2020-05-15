<?php


namespace App\Controller;


use App\Entity\Invitation;
use App\Entity\Offer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class InviteUserController
{
    private EntityManagerInterface $entityManager;
    private TokenGeneratorInterface $tokenGenerator;
    private SerializerInterface $serializer;
    private Swift_Mailer $mailer;

    public function __construct(Swift_Mailer $mailer, SerializerInterface $serializer, EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     name="invitation_invite_user",
     *     path="/invitations/invite/{offerId}",
     *     methods={"GET"}
     * )
     */
    public function __invoke(int $offerId) {
        $request  = Request::createFromGlobals();
        $email = $request->query->get("userEmail");
        $offer = $this->entityManager->getRepository(Offer::class)->find($offerId);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["email" => $email]);
        $invitation = new Invitation();
        $invitation->setApplicant($user);
        $invitation->setOffer($offer);
        $invitation->setToken($this->tokenGenerator->generateToken());
        $this->entityManager->persist($invitation);
        $this->entityManager->flush();
        $this->sendEmailConfirmation($invitation);
        return new JsonResponse($this->serializer->serialize($invitation, "json"));
    }

    private function sendEmailConfirmation(Invitation $invitation) : void
    {
        $message = (new Swift_Message('Invitation'))
            ->setFrom('hr@gmail.com')
            ->setTo($invitation->getApplicant()->getEmail())
            ->setBody(sprintf('Invitation token : %s', $invitation->getToken()));
        $this->mailer->send($message);
    }
}
