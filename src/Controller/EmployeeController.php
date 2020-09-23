<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class EmployeeController extends AbstractController
{

    private $serializer;
    private $em;


    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {

        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @Route("/addEmployee", name="api_add_employee", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request)
    {
        $data = $request->getContent();

        try {
            $employee = $this->serializer->deserialize($data, Employee::class, 'json');
            $this->em->persist($employee);
            $this->em->flush();
            return $this->json([
                'id' => $employee->getId(),
                'message' => 'Employee created',
            ], 201, []);
        } catch (NotEncodableValueException $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }

    }
}
