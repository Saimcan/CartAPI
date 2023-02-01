<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Dto\Response\Transformer\ProductResponseDtoTransformer;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractAPIController
{
    private ProductResponseDtoTransformer $productResponseDtoTransformer;
    public function __construct(ProductResponseDtoTransformer $productResponseDtoTransformer)
    {
        $this->productResponseDtoTransformer = $productResponseDtoTransformer;
    }

    #[Route('/product', name: 'product_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $products = $doctrine->getRepository(Product::class)->findAll();
        $dto = $this->productResponseDtoTransformer->transformFromObjects($products);

        return $this->json($dto);
    }
}
