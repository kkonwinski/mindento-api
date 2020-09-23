<?php

namespace App\Controller;

use App\Entity\Delegation;
use App\Entity\Employee;
use App\Repository\DelegationCountryRepository;
use App\Repository\DelegationRepository;
use App\Repository\EmployeeRepository;
use App\Service\ApiDelegationActions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api")
 */
class DelegationController extends AbstractController
{

    private $em;
    private $validator;
    private $employeeRepository;
    private $delegationCountryRepository;
    private $serializer;
    private $delegationActions;
    /**
     * @var DelegationRepository
     */
    private $delegationRepository;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;


    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, EmployeeRepository $employeeRepository, DelegationCountryRepository $delegationCountryRepository, ApiDelegationActions $delegationActions, DelegationRepository $delegationRepository, NormalizerInterface $normalizer, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->employeeRepository = $employeeRepository;
        $this->delegationCountryRepository = $delegationCountryRepository;
        $this->delegationActions = $delegationActions;

        $this->delegationRepository = $delegationRepository;
        $this->normalizer = $normalizer;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/delegation", name="delegation")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DelegationController.php',
        ]);
    }

    /**
     * @Route("/addDelegation",name="add_delegation",methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function add(Request $request)
    {
        try {
            $data = $request->getContent();
            $delegation = $this->serializer->deserialize($data, Delegation::class, 'json');


            $requestParameters = json_decode($data);
            $this->setDelegationCountry($requestParameters->country, $delegation);


            $delegationEmployee = $this->employeeRepository->find(["id" => $requestParameters->employeeId]);
            $this->delegationActions->isEmployeeOnDelegation($delegationEmployee);
            $delegation->setEmployee($delegationEmployee);


            $this->delegationActions->compareDelegationTimes($requestParameters->start, $requestParameters->end);


            $this->em->persist($delegation);
            $this->em->flush();
            return $this->json([
                'message' => 'Delegate created!!!'
            ], 201);


        } catch (\Exception $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }
    }


    /**
     * @param string $delegationCountry
     * @param Delegation $delegation
     */
    public function setDelegationCountry(string $delegationCountry, Delegation $delegation)
    {
        $delegationCountry = $this->delegationCountryRepository->findCountryByName($delegationCountry);
        $delegation->setCountry($delegationCountry);
    }

    /**
     * @Route("/showEmployeeDelegations/{id}", name="show_employee_delegations", methods={"GET"})
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function showAllEmployeeDelegations($id,SerializerInterface $serializer)
    {
        $employeeDelegations = $this->delegationRepository->findBy(["employee" => $id]);

foreach ($employeeDelegations as $employeeDelegation){
    $this->delegationActions->checkDiffTime($employeeDelegation->getStart(), $employeeDelegation->getEnd());
}
try {


            return $this->json($employeeDelegations, 200, [], ['groups' => "d"]);
        } catch (\Exception $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }
    }
}
