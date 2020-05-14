<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EmailConfirmationController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(
     *     name="user_email_confirmation",
     *     path="/users/confirm/{confirmationToken}",
     *     methods={"GET"},
     * )
     */
    public function __invoke(string $confirmationToken) {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["confirmationToken" => $confirmationToken]);
        if (!$user)
            throw new BadRequestHttpException();

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $this->entityManager->flush();

        return new Response($user);
    }
}
