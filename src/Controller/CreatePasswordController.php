<?php

namespace App\Controller;

use App\CompanySubdomain\SomeModule\Domain\Entity\Password;
use App\CompanySubdomain\SomeModule\Infrastructure\Exceptions\DuplicatePasswordNameError;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class CreatePasswordController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    #[Route('/api/create-password', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $jsonContent = $request->getContent();
        $requestData = json_decode($jsonContent, true);

        $name = $requestData['name'];
        $algorithm = $requestData['algorithm'] ?? 'random'; // Default algorithm
        $length = $requestData['length'] ?? 12; // Default length
        $includeUppercase = $requestData['includeUppercase'] ?? true;
        $includeLowercase = $requestData['includeLowercase'] ?? true;
        $includeNumbers = $requestData['includeNumbers'] ?? true;
        $includeSpecialChars = $requestData['includeSpecialChars'] ?? true;

        $generatedPassword = match ($algorithm) {
            'random' => $this->generateRandomPassword($length, $includeUppercase, $includeLowercase, $includeNumbers, $includeSpecialChars),
            'pronounceable' => $this->generatePronounceablePassword($length, $includeUppercase, $includeLowercase),
            'numbers' => $this->generateNumPassword($length),
            default => throw new \InvalidArgumentException('Invalid algorithm specified.'),
        };

        $password = new Password(RamseyUuid::uuid4()->toString(), $name, $generatedPassword, $this->getUser()->getId());

        try {
            $this->entityManager->persist($password);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicatePasswordNameError($password->getName());
        }

        return $this->json($password);
    }

    private function generateRandomPassword(int $length, bool $includeUppercase, bool $includeLowercase, bool $includeNumbers, bool $includeSpecialChars): string
    {
        $characters = '';
        $characters .= $includeUppercase ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
        $characters .= $includeLowercase ? 'abcdefghijklmnopqrstuvwxyz' : '';
        $characters .= $includeNumbers ? '0123456789' : '';
        $characters .= $includeSpecialChars ? '!@#$%^&*()-_+=[]{}|;:,.<>?/' : '';

        $password = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        return $password;
    }

    private function generatePronounceablePassword(int $length, bool $includeUppercase, bool $includeLowercase): string
    {
        $vowels = array('a', 'e', 'i', 'o', 'u');
        $consonants = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');

        $password = '';
        $vowelCount = count($vowels);
        $consonantCount = count($consonants);

        for ($i = 0; $i < $length; $i++) {
            $sourceArray = ($i % 2 == 0) ? $consonants : $vowels;

            $char = $sourceArray[rand(0, count($sourceArray) - 1)];

            if ($includeUppercase && $includeLowercase) {
                $char = (rand(0, 1) == 0) ? strtoupper($char) : $char;
            } elseif ($includeUppercase) {
                $char = strtoupper($char);
            }

            $password .= $char;
        }

        return $password;
    }

    private function generateNumPassword($length): string
    {
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= random_int(0, 9);
        }

        return $password;
    }
}
