<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EmailConfirmationController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
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

        return new JsonResponse($this->serializer->serialize($user, "json"));
    }
}
