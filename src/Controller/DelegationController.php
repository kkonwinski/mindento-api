<?php

namespace App\Controller;

use App\Entity\Delegation;
use App\Repository\DelegationCountryRepository;
use App\Repository\DelegationRepository;
use App\Repository\EmployeeRepository;
use App\Service\ApiDelegationActions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    private $delegationRepository;


    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, EmployeeRepository $employeeRepository, DelegationCountryRepository $delegationCountryRepository, ApiDelegationActions $delegationActions, DelegationRepository $delegationRepository, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->employeeRepository = $employeeRepository;
        $this->delegationCountryRepository = $delegationCountryRepository;
        $this->delegationActions = $delegationActions;
        $this->delegationRepository = $delegationRepository;
        $this->serializer = $serializer;
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
     * @param int $id
     * @return JsonResponse
     */
    public function showAllEmployeeDelegations(int $id): JsonResponse
    {
        $employeeDelegations = $this->delegationRepository->findEmployeeDelegations($id);

        foreach ($employeeDelegations as &$employeeDelegation) {
            $employeeDelegation = (object)$employeeDelegation;


            $isDelegateLongerThanEightHours = $this->delegationActions->checkDiffTime($employeeDelegation->start, $employeeDelegation->end);
            $delegateDays = $this->delegationActions->getNumberOfDelegateDays($employeeDelegation->start, $employeeDelegation->end);

            if ($isDelegateLongerThanEightHours == true) {
                if ($delegateDays > 7) {
                    $countCalendarDaysDelegation = $this->delegationActions->countCalendarDaysDelegation($employeeDelegation->start, $employeeDelegation->end);
                    $employeeDelegation->amountDoe = $this->delegationActions->calculateDoeAmountDelegation($countCalendarDaysDelegation, $employeeDelegation->amountDoe);
                }
            }
            $employeeDelegation->start = $this->delegationActions->formatDate($employeeDelegation->start);
            $employeeDelegation->end = $this->delegationActions->formatDate($employeeDelegation->end);
        }
        $employeeDelegationsJson = $this->serializer->serialize($employeeDelegations, 'json');

        try {
            return $this->json(json_decode($employeeDelegationsJson), 200);
        } catch (\Exception $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }
    }
}
