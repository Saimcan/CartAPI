<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\Response\Transformer\ProductResponseDtoTransformer;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractAPIController implements APICRUDInterface
{
    private ProductResponseDtoTransformer $productResponseDtoTransformer;
    public function __construct(ProductResponseDtoTransformer $productResponseDtoTransformer)
    {
        $this->productResponseDtoTransformer = $productResponseDtoTransformer;
    }

    #[Route('/product', name: 'product_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        // TODO: Implement list() method.
    }

    public function show(int $id, ManagerRegistry $doctrine)
    {
        // TODO: Implement show() method.
    }

    public function create(ManagerRegistry $doctrine, Request $request)
    {
        // TODO: Implement create() method.
    }

    public function update(int $id, ManagerRegistry $doctrine, Request $request)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id, ManagerRegistry $doctrine, Request $request)
    {
        // TODO: Implement delete() method.
    }
}
