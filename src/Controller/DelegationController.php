<?php

namespace App\Controller;

use App\Entity\Delegation;
use App\Entity\DelegationCountry;
use App\Entity\Employee;
use App\Repository\DelegationCountryRepository;
use App\Repository\EmployeeRepository;
use DateTime;
use Doctrine\Migrations\Configuration\Migration\Exception\JsonNotValid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
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
    /**
     * @var SerializerInterface
     */
    private $delegationCountry;
    private $delegation;
    /**
     * @var SerializerInterface
     */
    private $serializer;


    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, EmployeeRepository $employeeRepository, DelegationCountryRepository $delegationCountryRepository,SerializerInterface $serializer)
    {
        $this->delegationCountry = new Delegation();
        $this->delegation = new Delegation();
        $this->em = $em;
        $this->validator = $validator;
        $this->employeeRepository = $employeeRepository;
        $this->delegationCountryRepository = $delegationCountryRepository;
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
        $data = $request->getContent();
     //   $delegation = new Delegation();
        $delegation = $this->serializer->deserialize($data, Delegation::class, 'json');


        $requestParameters = json_decode($data);

        $delegationCountry = $this->delegationCountryRepository->findCountryByName($requestParameters->country);
        $delegation->setCountry($delegationCountry);


        $delegationEmployee = $this->employeeRepository->find(["id" => $requestParameters->employeeId]);
        $delegation->setEmployee($delegationEmployee);
        $requestParameters = json_encode($data);

        $delegation->setIsFinish(false);
//        $errors = $this->validator->validate($delegation);
//
//        if (count($errors) > 0) {
//            $errorsString = (string)$errors;
//            return new Response($errorsString);
//        }
        try {
            $this->em->persist($delegation);
            $this->em->flush();
            return $this->json([
                'message'=>'Delegate created!!!'
                ],201);


        } catch (NotEncodableValueException $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }
    }

}
