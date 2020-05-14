<?php


namespace App\Controller;


use App\Entity\Application;
use App\Entity\Offer;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class InviteUserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(
     *     name="invitation_invite_user",
     *     path="/invitations/invite",
     *     methods={"POST"}
     * )
     */
    public function __invoke(User $user, Offer $offer) {
//        $user = $this->userRepository->findOneBy(["email" => $inviteUserRequestParam->getUserEmail()]);
//        $offer = $this->userRepository->find($inviteUserRequestParam->getOfferId());
        dump($user);
        dump($offer);
        die();
        $application = new Application();
//        $application->set
//        $data->setConfirmationToken(null);
//        $data->setEnabled(true);
        $this->entityManager->flush();

//        return $data;
    }
}
