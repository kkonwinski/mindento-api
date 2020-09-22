<?php

namespace App\Controller;

use App\Entity\Delegation;
use App\Entity\DelegationCountry;
use App\Entity\Employee;
use App\Repository\DelegationCountryRepository;
use App\Repository\EmployeeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    private $serializer;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, EmployeeRepository $employeeRepository, DelegationCountryRepository $delegationCountryRepository)
    {

        $this->em = $em;
        $this->validator = $validator;
        $this->employeeRepository = $employeeRepository;
        $this->delegationCountryRepository = $delegationCountryRepository;
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

        $delegation = new Delegation();

        $parametersAsArray = json_decode($data);
        $delegationCountry = $this->delegationCountryRepository->findCountryByName($parametersAsArray->country);
        $delegation->setDelegationCountry($delegationCountry);


        $delegationEmployee = $this->employeeRepository->find(["id" => $parametersAsArray->employeeId]);
        $delegation->setEmployee($delegationEmployee);


        $startDate = $delegation->createDateTimeFormat($parametersAsArray->start);
        $delegation->setStartDelegation($startDate);


        $finishDate = $delegation->createDateTimeFormat($parametersAsArray->end);
        $delegation->setFinishDelegation($finishDate);

        $delegation->setIsFinish(false);
        $errors = $this->validator->validate($delegation);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Response($errorsString);
        }
        try {
            $this->em->persist($delegation);
            $this->em->flush();

            return $this->json($delegation, 201);

        } catch (NotEncodableValueException $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }
    }
}
