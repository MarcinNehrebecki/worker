<?php


namespace App\Service;


use App\Entity\DepartmentEntity;
use App\Entity\WorkerEntity;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class WorkerService extends ListService
{
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $name;

    public function __construct(Request $request)
    {
        $this->offset = $request->get('offset') ?? 0;
        $this->limit = $request->get('limit') ?? 0;
        $this->order = $request->get('order') ?? 'asc';
        $this->sort = $request->get('sort') ?? 'name';
        $this->firstName = $request->get('firstName') ?? '';
        $this->lastName = $request->get('lastName') ?? '';
        $this->name = $request->get('Name') ?? '';
    }

    /**
     * @param mixed $workers
     * @param array $reportList
     * @throws \Exception
     */
    public function prepareResultOnReport(array $workers, array &$reportList): void
    {
        $workersAjax = [];
        $i = 1 + $this->getOffset();
        /** @var WorkerEntity $row */
        foreach ($workers as $row) {
            $bonusPrice = $row->getDepartment()->getBonusPrice();
            $salary = $row->getSalary();
            $type = $row->getDepartment()->getType();
            if (DepartmentEntity::FIELD_PERCENT === $type) {
                $bonusPrice = ($salary * $bonusPrice) / 100;
            } else {
                $dateStarWorking = $row->getDateEmployment();
                $datetime1 = new DateTime($dateStarWorking->format('Y-m-d'));
                $datetime2 = new DateTime(date('Y-m-d'));
                $interval = $datetime1->diff($datetime2);
                $years = $interval->format('%Y');
                if ($years > 10 ) {
                    $years = 10;
                }
                $bonusPrice = $years * $bonusPrice;
            }
            $sum = $bonusPrice + $row->getSalary();
            $workersAjax[] = [
                'i' => $i,
                'id' => $row->getId(),
                'c.lastName' => $row->getLastName(),
                'c.firstName' => $row->getFirstName(),
                'c.salary' => $salary,
                'd.name' => $row->getDepartment()->getName(),
                'bonusPrice' => $bonusPrice,
                'd.type' => $type,
                'sum' => $sum,
            ];
            $i++;
        }
        $reportList['rows'] = $workersAjax;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}