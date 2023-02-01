<?php

declare(strict_types = 1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;

class CategoryResponseDto
{
    /**
     * @Serialization\Type("string")
     */
    public string $name;

    /**
     * @Serialization\Type("App\Dto\Response\ProductResponseDto")
     */
    public ProductResponseDto $products;
}
