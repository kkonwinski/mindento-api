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

    public function checkDiffTime($startTime, $endTime)
    {
        $startTime->format('Y-m-d H:i:s');
        $endTime->format('Y-m-d H:i:s');
        $dateDiff = date_diff($startTime, $endTime);
        $dayInHours=$dateDiff->days * 24;
        $hours=$dateDiff->h;
$delegateTime=$dayInHours+$hours;
        if ($delegateTime < 8) {
            return true;
        }

    }

    private function getNumberOfWeekendDays(\DateTimeInterface $startDate, \DateTimeInterface $endDate): int
    {
        $startNumber = (int) $startDate->format('N');
        $endNumber = (int) $endDate->format('N');
        $daysBetweenStartAndEnd = $endDate->diff($startDate)->d;

        $weekendDays = (int) (2 * ($daysBetweenStartAndEnd + $startNumber) / 7);
        $weekendDays = $weekendDays - ($startNumber == 7 ? 1 : 0) - ($endNumber == 7 ?  1 :  0);

        return $weekendDays;
    }
}