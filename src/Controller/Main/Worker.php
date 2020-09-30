<?php
/**
 * Created by PhpStorm.
 * User: Marcin Nehrebecki
 */

namespace App\Controller\Main;

use App\Controller\AbstractBase;
use App\Entity\WorkerEntity;
use App\Repository\WorkerRepository;
use App\Service\WorkerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Worker extends AbstractBase
{
    /**
     * @Route("workerNew", name="workerNew")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $worker = new WorkerEntity();
        $form = $this->createForm(\App\Form\WorkerType::class, $worker, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $worker = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($worker);
            $entityManager->flush();

            return $this->redirectToRoute('workers');
        }
        return $this->render('worker/worker_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/workerList", name="workerList")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getWorkerList(Request $request)
    {
        $workerService = new WorkerService($request);

        $entityManager = $this->getDoctrine()->getManager();
        /** @var WorkerRepository $worker */
        $worker = $entityManager->getRepository(WorkerEntity::class);
        $workers = $worker->findListOnWorker($workerService);
        $workerList = [
            'rows' => [],
            'total' => $worker->findTotalOnWorker($workerService)
        ];
        $workersAjax = [];
        $i = 1 + $workerService->getOffset();
        /** @var WorkerEntity $row */
        foreach ($workers as $row) {
            $workersAjax[] = [
                'i' => $i,
                'id' => $row->getId(),
                'lastName' => $row->getLastName(),
                'firstName' => $row->getFirstName(),
                'salary' => $row->getSalary(),
                'departmentName' => $row->getDepartment()->getName(),
            ];
            $i++;
        }
        $workerList['rows'] = $workersAjax;

        return $this->json($workerList);
    }

    /**
     * @Route("/workers", name="workers")
     * @throws \Exception
     */
    public function workerList()
    {
        $data = [
            'title' => 'Lista Działów'
        ];

        return $this->render('worker/worker_list.html.twig', $data);
    }

}