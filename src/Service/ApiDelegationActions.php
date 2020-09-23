<?php


namespace App\Service;


use App\Entity\Employee;
use App\Repository\DelegationRepository;
use JsonException;


class ApiDelegationActions
{

    private $delegationRepository;

    public function __construct(DelegationRepository $delegationRepository)
    {
        $this->delegationRepository = $delegationRepository;
    }

    /**
     * @param string $startTime
     * @param string $endTime
     * @throws JsonException
     */
    public function compareDelegationTimes(string $startTime, string $endTime)
    {
        if (strtotime($startTime) > strtotime($endTime)) {
            throw new JsonException('Start date is bigger or equal end time!!!', 500);
        }

    }

    /**
     * @param Employee $employee
     * @return bool
     * @throws JsonException
     */
    public function isEmployeeOnDelegation(Employee $employee)
    {
        $isEmployeeOnDelegation = $this->delegationRepository->findEmployeeOnDelegation($employee);
        if (empty($isEmployeeOnDelegation)) {
            return true;
        } else {
            throw new JsonException('Employee is actually on delegation', 500);
        }
    }
}