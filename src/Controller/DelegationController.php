<?php

namespace App\Controller;

use App\Entity\Delegation;
use App\Repository\DelegationCountryRepository;
use App\Repository\EmployeeRepository;
use App\Service\ApiDelegationActions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
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


    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, EmployeeRepository $employeeRepository, DelegationCountryRepository $delegationCountryRepository, SerializerInterface $serializer, ApiDelegationActions $delegationActions)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->employeeRepository = $employeeRepository;
        $this->delegationCountryRepository = $delegationCountryRepository;
        $this->serializer = $serializer;
        $this->delegationActions = $delegationActions;
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
            //   $delegation = new Delegation();
            $delegation = $this->serializer->deserialize($data, Delegation::class, 'json');


            $requestParameters = json_decode($data);

            $delegationCountry = $this->delegationCountryRepository->findCountryByName($requestParameters->country);
            $delegation->setCountry($delegationCountry);


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

}
