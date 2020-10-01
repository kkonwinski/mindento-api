<?php


namespace App\Service;

use App\Entity\Employee;
use App\Repository\DelegationRepository;
use DateTime;
use JsonException;

class ApiDelegationActions
{

    private $delegationRepository;

    public function __construct(DelegationRepository $delegationRepository)
    {
        $this->delegationRepository = $delegationRepository;
    }


    /**
     * @param array $employeeDelegations
     * @return array
     */

    public function getDelegateData(array $employeeDelegations): array
    {
        foreach ($employeeDelegations as &$employeeDelegation) {
            $employeeDelegation = (object)$employeeDelegation;
            $startDate = $employeeDelegation->start;
            $endDate = $employeeDelegation->end;
            $isDelegateLongerThanEightHours = $this->checkDiffTime($startDate, $endDate);
            $delegateDays = $this->getNumberOfDelegateDays($startDate, $endDate);

            if ($isDelegateLongerThanEightHours == true && $delegateDays > 7) {
                $countCalendarDaysDelegation = $this->countCalendarDaysDelegation($startDate, $endDate);

                $employeeDelegation->amountDoe = $this->calculateDoeAmountDelegation($countCalendarDaysDelegation, $employeeDelegation->amountDoe);
            }
            $employeeDelegation->start = $this->formatDate($startDate);
            $employeeDelegation->end = $this->formatDate($endDate);
        }
        return $employeeDelegations;
    }


    /**
     * @param string $startTime
     * @param string $endTime
     * @throws JsonException
     */
    public function compareDelegationTimes(string $startTime, string $endTime)
    {
        strtotime($startTime) > strtotime($endTime) ?: $this->setJsonException('Start date is bigger or equal end time!!!', 500);
    }


    /**
     * @param Employee $employee
     * @return bool
     * @throws JsonException
     */
    public function isEmployeeOnDelegation(Employee $employee): bool
    {
        $isEmployeeOnDelegation = $this->delegationRepository->findEmployeeOnDelegation($employee);
        empty($isEmployeeOnDelegation) ? true : $this->setJsonException('Employee is actually on delegation', 500);
    }

    /**
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @return bool
     */
    public function checkDiffTime(DateTime $startTime, DateTime $endTime): bool
    {
        $this->formatDate($startTime);
        $this->formatDate($endTime);
        $dateDiff = date_diff($startTime, $endTime);
        $dayInHours = $dateDiff->days * 24;
        $hours = $dateDiff->h;
        $delegateTime = $dayInHours + $hours;
        if ($delegateTime > 8) {
            return true;
        }
    }

    public function getNumberOfDelegateDays(DateTime $startDate, DateTime $endDate)
    {
        $startNumber = (int)$this->formatDate($startDate);
        $endNumber = (int)$this->formatDate($endDate);
        $daysBetweenStartAndEnd = $this->countCalendarDaysDelegation($startDate, $endDate);

        $weekendDays = (int)round($daysBetweenStartAndEnd / 7) * 2;

        $weekendDays = $weekendDays - ($startNumber == 7 ? 1 : 0) - ($endNumber == 7 ? 1 : 0);
        return $this->getDelegateNumberDaysWithoutWeekend($daysBetweenStartAndEnd, $weekendDays);
    }

    public function getDelegateNumberDaysWithoutWeekend(int $delegateDays, int $weekendDays): int
    {
        return $delegateDays - $weekendDays;
    }

    public function countCalendarDaysDelegation(\DateTimeInterface $startDate, \DateTimeInterface $endDate)
    {

        return $endDate->diff($startDate)->days;
    }

    public function calculateDoeAmountDelegation($calendarDays, $amountDoe)
    {
        return $calendarDays + (($calendarDays - 7) * $amountDoe * 2);
    }


    /**
     * @param DateTime $date
     * @return string
     */
    public function formatDate(DateTime $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    /**
     * @param string $content
     * @param int $code
     * @return JsonException
     * @throws JsonException
     */
    public function setJsonException(string $content, int $code): JsonException
    {
        throw new JsonException(sprintf('%s', $content), $code);
    }
}
