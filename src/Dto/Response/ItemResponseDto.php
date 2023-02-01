<?php

declare(strict_types = 1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
class ItemResponseDto
{
    /**
     * @Serialization\Type("int")
     */
    public int $productId;

    /**
     * @Serialization\Type("int")
     */
    public int $quantity;

    /**
     * @Serialization\Type("int")
     */
    public string $unitPrice;

    /**
     * @Serialization\Type("string")
     */
    public string $total;
}
