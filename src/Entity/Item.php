<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $unitPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        //check if stock is available
        $stock = $this->getProduct()->getStock();

        if($stock < $quantity){
            throw new \Exception("Not enough stock for ". $this->getProduct()->getName() .". ".
                "Stock available: ".$stock.". You requested: ".$quantity
                , 50);
        }

        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(string $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraints('product', [
            new Assert\NotNull()
        ]);

        $metadata->addPropertyConstraints('quantity', [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Positive()
        ]);

        $metadata->addPropertyConstraints('unitPrice', [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Positive()
        ]);

        $metadata->addPropertyConstraints('total', [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Positive()
        ]);
    }
}
