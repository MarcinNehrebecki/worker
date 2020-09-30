<?php


namespace App\Controller\Main;


use App\Controller\AbstractBase;
use App\Entity\WorkerEntity;
use App\Repository\WorkerRepository;
use App\Service\WorkerService;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @throws \Exception
     */
    public function getReportList(Request $request): JsonResponse
    {
        $workerService = new WorkerService($request);

        $entityManager = $this->getDoctrine()->getManager();
        /** @var WorkerRepository $worker */
        $worker = $entityManager->getRepository(WorkerEntity::class);
        $workers = $worker->findListOnWorkerReport($workerService);
        $reportList = [
            'rows' => [],
            'total' => $worker->findTotalOnWorker($workerService)
        ];

        $workerService->prepareResultOnReport($workers, $reportList);

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