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
    {

    }

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

        // Perform password generation based on the selected algorithm
        $generatedPassword = match ($algorithm) {
            'random' => $this->generateRandomPassword($length, $includeUppercase, $includeLowercase, $includeNumbers, $includeSpecialChars),
            'pronounceable' => $this->generatePronounceablePassword($length),
            'passphrase' => $this->generatePassphrase($length),
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

    private function generatePronounceablePassword(int $length): string
    {
        $vowels = array('a', 'e', 'i', 'o', 'u');
        $consonants = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');

        $password = '';
        $vowelCount = count($vowels);
        $consonantCount = count($consonants);

        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 == 0) {
                $password .= $consonants[rand(0, $consonantCount - 1)];
            } else {
                $password .= $vowels[rand(0, $vowelCount - 1)];
            }
        }

        return $password;
    }

    private function generatePassphrase(): string
    {
        $wordList = array('apple', 'banana', 'orange', 'grape', 'kiwi', 'melon', 'pear', 'peach');
        $separator = '-';
        $passphrase = '';

        for ($i = 0; $i < 4; $i++) {
            $index = rand(0, count($wordList) - 1);
            $passphrase .= $wordList[$index];

            if ($i < 4 - 1) {
                $passphrase .= $separator;
            }
        }

        return $passphrase;
    }
}
