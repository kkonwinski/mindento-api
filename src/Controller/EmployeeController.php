<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class EmployeeController extends AbstractController
{

    /**
     * @Route("/api/addEmployee", name="api_add_employee", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $data = $request->getContent();

        try {
            $employee = $serializer->deserialize($data, Employee::class, 'json');
            $em->persist($employee);
            $em->flush();
            return $this->json([
                'id' => $employee->getId(),
                'message' => 'Employee created',
            ], 201, []);
        } catch (NotEncodableValueException $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }

    }
}
