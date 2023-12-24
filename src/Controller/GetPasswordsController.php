<?php

namespace App\Controller;

use App\CompanySubdomain\SomeModule\Domain\Entity\Password;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class GetPasswordsController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/api/get-password-list', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $userId = $this->getUser()->getId();

        $passwords = $this->entityManager->getRepository(Password::class)->findBy(['userId' => $userId]);

        $data = [];
        foreach ($passwords as $password) {
            $data[] = [
                'id' => $password->getId(),
                'name' => $password->getName(),
                'value' => $password->getPasswordValue(),
            ];
        }

        return $this->json($data);
    }
}
