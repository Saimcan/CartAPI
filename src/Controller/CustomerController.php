<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'customer_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        //todo: check if request is valid json
        $customers = $doctrine->getRepository(Customer::class)->findAll();
        $data = [];

        /**
         * @var $customer Customer
         */
        foreach ($customers as $customer){
            $data = [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'since' => $customer->getSince()->format('Y-m-d'),
                'revenue' => $customer->getRevenue()
            ];
        }

        return $this->json($data);
    }

    #[Route('/customer/{id}', name: 'customer_show', methods: 'GET')]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        //todo: check if request is valid json
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
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        //todo: check if request is valid json
        $entityManager = $doctrine->getManager();

        $customer = new Customer();
        $customer->setName($request->get('name'));
        $customer->setSince($request->get('since'));
        $customer->setRevenue($request->get('revenue'));

        $entityManager->persist($customer);
        $entityManager->flush();

        return $this->json('Created a new customer successfully with name ' . $customer->getName());
    }

    #[Route('/customer', name: 'customer_edit', methods: 'PUT')]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        //todo: check if request is valid json
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);

        if(!$customer){
            return $this->json('No customer found for id: '. $id, 404);
        }

        /**
         * @var Customer $customer
         */
        $customer->setName($request->get('name'));
        $customer->setSince($request->get('since'));
        $customer->setRevenue($request->get('revenue'));

        $data =  [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'since' => $customer->getDescription(),
            'revenue' => $customer->getRevenue()
        ];

        return $this->json($data);
    }

    #[Route('/customer', name: 'customer_delete', methods: 'DELETE')]
    public function delete(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
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
