<?php

declare(strict_types = 1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;

class OrderResponseDto
{
    /**
     * @Serialization\Type("int")
     */
    public int $id;

    /**
     * @Serialization\Type("int")
     */
    public int $customerId;

    /**
     * @Serialization\Type("array<App\Dto\Response\ItemResponseDto>")
     */
    public iterable $items;

    /**
     * @Serialization\Type("double")
     */
    public string $total;
}
