<?php

declare(strict_types = 1);

namespace App\Dto\Response\Transformer;

use App\Dto\Exception\UnexpectedTypeException;
use App\Entity\Customer;
use App\Dto\Response\CustomerResponseDto;

class CustomerResponseDtoTransformer extends AbstractResponseDtoTransformer
{
    /**
     * @param Customer $customer
     */
    public function transformFromObject($customer): CustomerResponseDto
    {
        if(!$customer instanceof Customer){
            throw new UnexpectedTypeException('Excepted type of Customer but got '. \get_class($customer));
        }

        $dto = new CustomerResponseDto();
        $dto->name = $customer->getName();
        $dto->since = $customer->getSince();
        $dto->revenue = $customer->getRevenue();

        return $dto;
    }
}
