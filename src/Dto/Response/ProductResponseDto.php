<?php

declare(strict_types = 1);

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
class ProductResponseDto
{
    /**
     * @Serialization\Type("int")
     */
    public int $id;

    /**
     * @Serialization\Type("string")
     */
    public string $name;

    /**
     * @Serialization\Type("int")
     */
    public int $category;

    /**
     * @Serialization\Type("string")
     */
    public string $price;

    /**
     * @Serialization\Type("string")
     */
    public int $stock;
}
