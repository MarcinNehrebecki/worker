<?php


namespace App\Controller\Main;


use App\Controller\AbstractBase;
use App\Entity\DepartmentEntity;
use App\Entity\WorkerEntity;
use App\Repository\WorkerRepository;
use App\Service\WorkerService;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Report extends AbstractBase
{


    /**
     * @Route("/reportList", name="reportList")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getReportList(Request $request)
    {
        $workerService = new WorkerService($request);

        $entityManager = $this->getDoctrine()->getManager();
        /** @var WorkerRepository $worker */
        $worker = $entityManager->getRepository(WorkerEntity::class);
        $workers = $worker->findListOnWorker($workerService);
        $reportList = [
            'rows' => [],
            'total' => $worker->findTotalOnWorker($workerService)
        ];
        $workersAjax = [];
        $i = 1 + $workerService->getOffset();
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
                'lastName' => $row->getLastName(),
                'firstName' => $row->getFirstName(),
                'salary' => $salary,
                'departmentName' => $row->getDepartment()->getName(),
                'bonusPrice' => $bonusPrice,
                'type' => $type,
                'sum' => $sum,
            ];
            $i++;
        }
        $reportList['rows'] = $workersAjax;

        return $this->json($reportList);
    }

    /**
     * @Route("/report", name="report")
     * @throws \Exception
     */
    public function reportList()
    {
        $data = [
            'title' => 'Raport Miesięczny'
        ];

        return $this->render('raport/raport_list.html.twig', $data);
    }


}