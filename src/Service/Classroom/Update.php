<?php

declare(strict_types=1);

namespace App\Service\Classroom;

use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Update
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
     * Update constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param \App\Contract\Classroom $classroomContract
     * @param Classroom $classroom
     * @return Classroom
     */
    public function execute(\App\Contract\Classroom $classroomContract, Classroom $classroom): Classroom
    {
        foreach ((new \ReflectionClass($classroomContract))->getProperties() as $property) {
            $propName = $property->getName();

            if ($classroomContract->{$propName} !== null) {
                $setter = $this->getSetterName($property);
                $classroom->$setter($property->getValue($classroomContract));
            }
        }

        $errors = $this->validator->validate($classroom);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidationFailedException($errorsString, $errors);
        }

        $this->entityManager->persist($classroom);
        $this->entityManager->flush();

        return $classroom;
    }

    /**
     * @param \ReflectionProperty $property
     * @return string
     */
    private function getSetterName(\ReflectionProperty $property): string
    {
        return 'set' . ucfirst($property->getName());
    }
}


