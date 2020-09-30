<?php
/**
 * Created by PhpStorm.
 * User: Marcin Nehrebecki
 */

namespace App\Controller\Main;

use App\Controller\AbstractBase;
use App\Entity\DepartmentEntity;
use App\Repository\DepartmentRepository;
use App\Service\DepartmentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Department extends AbstractBase
{
    /**
     * @Route("departmentNew", name="departmentNew")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $department = new DepartmentEntity();
        $form = $this->createForm(\App\Form\DepartmentType::class, $department, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $department = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('departments');
        }
        return $this->render('department/department_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/departmentList", name="departmentList")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getClientList(Request $request)
    {
        $departmentService = new DepartmentService($request);

        $entityManager = $this->getDoctrine()->getManager();
        /** @var DepartmentRepository $department */
        $department = $entityManager->getRepository(DepartmentEntity::class);
        $departments = $department->findListOnDepartment($departmentService);
        $clientList = [
            'rows' => [],
            'total' => $department->findTotalOnDepartment($departmentService)
        ];
        $clientsAjax = [];
        $i = 1 + $departmentService->getOffset();
        /** @var DepartmentEntity $row */
        foreach ($departments as $row) {
            $clientsAjax[] = [
                'i' => $i,
                'id' => $row->getId(),
                'name' => $row->getName(),
                'bonusPrice' => $row->getBonusPrice(),
                'type' => $row->getType(),
                'workers' => count($row->getWorkers()),
            ];
            $i++;
        }
        $clientList['rows'] = $clientsAjax;

        return $this->json($clientList);
    }

    /**
     * @Route("/departments", name="departments")
     * @throws \Exception
     */
    public function clientList()
    {
        $data = [
            'title' => 'Lista Działów'
        ];

        return $this->render('department/department_list.html.twig', $data);
    }

}