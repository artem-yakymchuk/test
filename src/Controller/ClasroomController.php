<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use App\Service\Classroom\Create;
use App\Service\Classroom\Delete;
use App\Service\Classroom\ToggleState;
use App\Service\Classroom\Update;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ClasroomController extends AbstractController
{
    /**
     * @Route("/classroom", name="classroom_list", methods={"GET"})
     */
    public function getList(ClassroomRepository $classroomRepository): JsonResponse
    {
        return $this->json($classroomRepository->findAll());
    }

    /**
     * @Route("/classroom/active", name="classroom_get_active", methods={"GET"})
     */
    public function getActive(ClassroomRepository $classroomRepository): JsonResponse
    {
        return $this->json($classroomRepository->getActive());

    }

    /**
     * @Route("/classroom/{id}", name="classroom_get", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function getClassroom(ClassroomRepository $classroomRepository, $id): JsonResponse
    {
        $classroom = $classroomRepository->find($id);

        if (!$classroom){
            $data = [
                'status' => 404,
                'errors' => ["Classroom not found"],
            ];

            return $this->json($data, 404);
        }

        return $this->json($classroom);
    }

    /**
     * @Route("/classroom", name="classroom_create", methods={"POST"})
     */
    public function create(Create $create, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $classroom = $serializer->deserialize(
            $request->getContent(),
            Classroom::class,
            'json'
        );

        try {
            $classroom = $create->execute($classroom);
        } catch (ValidationFailedException $e) {
            return $this->json(['status' => 400, 'error' => $e->getValue()]);
        }

        return $this->json($classroom);
    }

    /**
     * @Route("/classroom/{id}", name="classroom_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function update(
        Update $update,
        Request $request,
        SerializerInterface $serializer,
        ClassroomRepository $classroomRepository,
        $id
    ): JsonResponse
    {
        $classroomEntity = $classroomRepository->find($id);

        if (!$classroomEntity) {
            $data = [
                'status' => 404,
                'errors' => "Classroom not found",
            ];
            return $this->json($data, 404);
        }

        $classroom = $serializer->deserialize(
            $request->getContent(),
            \App\Contract\Classroom::class,
            'json'
        );

        try {
            $classroom = $update->execute($classroom, $classroomEntity);
        } catch (\Exception $e) {
            return $this->json(['status' => 400, 'error' => $e->getMessage()]);
        }

        return $this->json($classroom);
    }

    /**
     * @Route("/classroom/{id}", name="classroom_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Delete $delete, $id): JsonResponse
    {
        try {
            $delete->execute($id);
        }catch (\Exception $e) {
            return $this->json(['status' => 400, 'error' => $e->getMessage()]);
        }

        return $this->json(
            [
                'status' => 200,
                'errors' => "Entity deleted successfully",
            ]
        );
    }

    /**
     * @Route("/classroom/{id}", name="classroom_toggle_state", methods={"PATCH"}, requirements={"id"="\d+"})
     */
    public function toggleState(ToggleState $toggleState, $id): JsonResponse
    {
        try {
            $classroom = $toggleState->execute($id);
        } catch (\Exception $e) {
            return $this->json(['status' => 400, 'error' => $e->getMessage()]);
        }

        return $this->json($classroom);
    }
}
