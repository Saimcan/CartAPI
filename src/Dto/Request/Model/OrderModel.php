<?php

namespace App\Dto\Request\Model;

use Symfony\Component\Validator\Constraints as Assert;

class OrderModel
{
    #[Assert\Positive(message: "Order id must be a positive integer value.")]
    public int $id;

    #[Assert\Positive(message: "customerId must be a positive integer value.")]
    public int $customerId;

    #[Assert\Valid]
    public array $items;

    #[Assert\Type(type: "string", message: "Order total must be a string float value.")]
    public string $total;
}
