<?php

declare(strict_types = 1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
class CustomerResponseDto
{
    /**
     * @Serialization\Type("string")
     */
    public string $name;

    /**
     * @Serialization\Type("Date<'Y-m-d'>")
     */
    public \DateTimeInterface $since;

    /**
     * @Serialization\Type("string")
     */
    public string $revenue;

    /**
     * @Serialization\Type ("App\Dto\Response\CustomerResponseDto")
     */
    public CustomerResponseDto $customer;
}
