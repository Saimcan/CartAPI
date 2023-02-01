<?php

declare(strict_types = 1);

namespace App\Dto\Response\Transformer;

use App\Dto\Exception\UnexpectedTypeException;
use App\Entity\Product;
use App\Dto\Response\ProductResponseDto;

class ProductResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Product $product
     */
    public function transformFromObject($product): ProductResponseDto
    {
        if(!$product instanceof Product){
            throw new UnexpectedTypeException('Excepted type of Product but got '. \get_class($product));
        }

        $dto = new ProductResponseDto();
        $dto->id = $product->getId();
        $dto->name = $product->getName();
        $dto->category = $product->getCategory()->getId();
        $dto->price = $product->getPrice();
        $dto->stock = $product->getStock();

        return $dto;
    }
}
