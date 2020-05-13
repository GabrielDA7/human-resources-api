<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmailConfirmationController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(User $data, $confirmationToken) {
        if(!$confirmationToken || $data->getConfirmationToken() != $confirmationToken) {
            throw new BadRequestHttpException();
        }

        $data->setConfirmationToken(null);
        $data->setEnabled(true);
        $this->entityManager->flush();

        return $data;
    }
}
