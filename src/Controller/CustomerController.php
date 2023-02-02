<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class CustomerController extends AbstractAPIController implements APICRUDInterface
{
    #[Route('/customer', name: 'customer_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        // TODO: Implement list() method.
    }

    #[Route('/customer/{id}', name: 'customer_show', methods: 'GET')]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        // TODO: Implement show() method.
    }

    #[Route('/customer', name: 'customer_new', methods: 'POST')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        // TODO: Implement create() method.
    }

    #[Route('/customer/{id}', name: 'customer_update', methods: 'PUT')]
    public function update(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        // TODO: Implement update() method.
    }

    #[Route('/customer/{id}', name: 'customer_delete', methods: 'DELETE')]
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // TODO: Implement delete() method.
    }
}
