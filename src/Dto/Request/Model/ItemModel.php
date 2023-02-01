<?php

namespace App\Dto\Request\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ItemModel
{
    #[Assert\Positive(message: "productId must be a positive integer value.")]
    public int $productId;

    #[Assert\Positive(message: "quantity must be a positive integer value.")]
    public int $quantity;

    #[Assert\Type(type: "string", message: "unitPrice must be a string float value.")]
    public string $unitPrice;

    #[Assert\Type(type: "string", message: "total must be a string float value.")]
    public string $total;

}
