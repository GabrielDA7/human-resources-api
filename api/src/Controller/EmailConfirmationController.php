<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EmailConfirmationController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke($confirmationToken) {
        $user = $this->userRepository->findOneBy(["confirmationToken" => $confirmationToken]);
        if (!$user)
            throw new BadRequestHttpException();

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $this->entityManager->flush();

        return $user;
    }
}
