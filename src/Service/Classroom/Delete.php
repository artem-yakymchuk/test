<?php

declare(strict_types=1);

namespace App\Service\Classroom;

use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class Delete
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
     * Delete constructor.
     * @param EntityManagerInterface $entityManager
     * @param ClassroomRepository $classroomRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ClassroomRepository $classroomRepository) {
        $this->entityManager = $entityManager;
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * @param int $id
     * @throws EntityNotFoundException
     */
    public function execute(int $id): void
    {
        $classroomEntity = $this->classroomRepository->find($id);
        if (!$classroomEntity) {
            throw new EntityNotFoundException('Not found classroom by ID:' . $id);
        }

        $this->entityManager->remove($classroomEntity);
        $this->entityManager->flush();
    }
}