<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class CustomerController extends AbstractAPIController implements APICRUDInterface
{
    #[Route('/customer', name: 'customer_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $customers = $doctrine->getRepository(Customer::class)->findAll();
        $data = [];

        /**
         * @var $customer Customer
         */
        foreach ($customers as $customer){
            $data[] = [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'since' => $customer->getSince()->format('Y-m-d'),
                'revenue' => $customer->getRevenue()
            ];
        }

        return $this->json($data);
    }

    #[Route('/customer/{id}', name: 'customer_show', methods: 'GET')]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $customer = $doctrine->getRepository(Customer::class)->find($id);
        if(!$customer){
            return $this->json('No customer found for id: ' . $id, 404);
        }

        /**
         * @var $customer Customer
         */
        $data =  [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'since' => $customer->getSince()->format('Y-m-d'),
            'revenue' => $customer->getRevenue()
        ];

        return $this->json($data);
    }

    #[Route('/customer', name: 'customer_new', methods: 'POST')]
    public function create(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        //validate form with symfony form builder and types
        $form = $this->buildForm(\CustomerType::class);
        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid()){
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();

        $customer = new Customer();
        $customer->setName($request->get('name'));
        $customer->setSince(new \DateTime(date('Y-m-d', strtotime($request->get('since')))));
        $customer->setRevenue($request->get('revenue'));

        $entityManager->persist($customer);
        $entityManager->flush();

        return $this->json('Created a new customer successfully with name ' . $customer->getName());
    }

    #[Route('/customer/{id}', name: 'customer_update', methods: 'PUT')]
    public function update(int $id, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);

        if(!$customer){
            return $this->json('No customer found for id: '. $id, Response::HTTP_NOT_FOUND);
        }

        //validate form with symfony form builder and types
        $form = $this->buildForm(\CustomerType::class, $customer, [
            "method" => "PUT"
        ]);
        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid()){
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var Customer $customer
         */
        $customer->setName($request->get('name'));
        $customer->setSince(new \DateTime(date('Y-m-d', strtotime($request->get('since')))));
        $customer->setRevenue($request->get('revenue'));

        $entityManager->persist($customer);
        $entityManager->flush();

        $data =  [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'since' => $customer->getSince(),
            'revenue' => $customer->getRevenue()
        ];

        return $this->json($data);
    }

    #[Route('/customer/{id}', name: 'customer_delete', methods: 'DELETE')]
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);

        if(!$customer){
            return $this->json('No customer found for id: ' . $id, 404);
        }

        $entityManager->remove($customer);
        $entityManager->flush();

        return $this->json('Deleted a customer successfully with id: ' . $id);
    }
}
