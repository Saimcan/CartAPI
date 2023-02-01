<?php

declare(strict_types = 1);

namespace App\Dto\Response\Transformer;

use App\Dto\Exception\UnexpectedTypeException;
use App\Dto\Response\ItemResponseDto;
use App\Entity\Item;

class ItemResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Item $item
     */
    public function transformFromObject($item): ItemResponseDto
    {
        if(!$item instanceof Item){
            throw new UnexpectedTypeException('Excepted type of Item but got '. \get_class($item));
        }

        $dto = new ItemResponseDto();
        $dto->productId = $item->getProduct()->getId();
        $dto->quantity = $item->getQuantity();
        $dto->unitPrice = $item->getUnitPrice();
        $dto->total = $item->getTotal();

        return $dto;
    }
}
