<?php

declare(strict_types=1);

namespace App\Service\Classroom;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ToggleState
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ClassroomRepository
     */
    private ClassroomRepository $classroomRepository;

    /**
     * ToggleState constructor.
     * @param EntityManagerInterface $entityManager
     * @param ClassroomRepository $classroomRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ClassroomRepository $classroomRepository) {
        $this->entityManager = $entityManager;
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * @param int $id
     * @return Classroom
     * @throws EntityNotFoundException
     */
    public function execute(int $id): Classroom
    {
        $classroomEntity = $this->classroomRepository->find($id);

        if (!$classroomEntity) {
            throw new EntityNotFoundException();
        }

        $classroomEntity->setIsActive($classroomEntity->getIsActive() ? false : true);

        $this->entityManager->persist($classroomEntity);
        $this->entityManager->flush();

        return $classroomEntity;
    }
}