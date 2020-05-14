<?php


namespace App\Controller;


use App\Entity\Application;
use App\Entity\Offer;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InviteUserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(InviteUserRequestParam $inviteUserRequestParam) {
        $user = $this->userRepository->findOneBy(["email" => $inviteUserRequestParam->getUserEmail()]);
        $offer = $this->userRepository->find($inviteUserRequestParam->getOfferId());
        $application = new Application();
//        $application->set
//        $data->setConfirmationToken(null);
//        $data->setEnabled(true);
        $this->entityManager->flush();

//        return $data;
    }
}
