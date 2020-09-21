<?php

namespace App\Controller;

use App\Entity\Delegation;
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

/**
 * @Route("/api")
 */
class DelegationController extends AbstractController
{
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
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, EmployeeRepository $employeeRepository, DelegationCountryRepository $delegationCountryRepository)
    {
        $data = $request->getContent();
        $delegation = new Delegation();

        $parametersAsArray = json_decode($data);
        $delegationCountry = $delegationCountryRepository->find(["id" => $parametersAsArray->delegationId]);
        $delegation->setDelegationCountry($delegationCountry);
        $delegationEmployee = $employeeRepository->findOneBy(["id" => $parametersAsArray->employeeId]);
        $delegation->setEmployee($delegationEmployee);


        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $parametersAsArray->start);
        $delegation->setStartDelegation($startDate);

        $finishDate = \DateTime::createFromFormat('Y-m-d H:i:s', $parametersAsArray->finish);
        $delegation->setFinishDelegation($finishDate);
        $delegation->setIsFinish(false);


        try {
            //$delegation1 = $serializer->deserialize(json_encode($delegation), Delegation::class, 'json');
            $em->persist($delegation);
            $em->flush();
            return $this->json($delegation, 201, []);


            dd($delegation);
        } catch (NotEncodableValueException $valueException) {
            return $this->json(['status' => 400, 'message' => $valueException->getMessage()], 400);
        }
    }
}
