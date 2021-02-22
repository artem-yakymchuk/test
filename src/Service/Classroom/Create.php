<?php

declare(strict_types=1);

namespace App\Service\Classroom;

use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Create
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * Create constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param Classroom $classroom
     * @return Classroom
     */
    public function execute(Classroom $classroom): Classroom
    {
        $errors = $this->validator->validate($classroom);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidationFailedException($errorsString, $errors);
        }
        $this->entityManager->persist($classroom);
        $this->entityManager->flush();

        return $classroom;
    }
}