<?php

declare(strict_types = 1);

namespace App\Dto\Response\Transformer;

use App\Dto\Exception\UnexpectedTypeException;
use App\Dto\Response\OrderResponseDto;
use App\Entity\Order;

class OrderResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    private ItemResponseDtoTransformer $itemResponseDtoTransformer;

    public function __construct(ItemResponseDtoTransformer $itemResponseDtoTransformer){
        $this->itemResponseDtoTransformer = $itemResponseDtoTransformer;
    }

    /**
     * @param Order $order
     */
    public function transformFromObject($order): OrderResponseDto
    {
        if(!$order instanceof Order){
            throw new UnexpectedTypeException('Excepted type of Order but got '. \get_class($order));
        }

        $dto = new OrderResponseDto();
        $dto->id = $order->getId();
        $dto->customerId = $order->getCustomer()->getId();
        $dto->items = $this->itemResponseDtoTransformer->transformFromObjects($order->getItems());
        $dto->total = $order->getTotal();

        return $dto;
    }
}
