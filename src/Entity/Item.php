<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\OneToMany(mappedBy: 'items', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $unitPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'items')]
    private Collection $ordersPlaced;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->ordersPlaced = new ArrayCollection();
    }

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
            //todo: replace with an exception type so that it will be handled on controller
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

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersPlaced(): Collection
    {
        return $this->ordersPlaced;
    }

    public function addOrdersPlaced(Order $ordersPlaced): self
    {
        if (!$this->ordersPlaced->contains($ordersPlaced)) {
            $this->ordersPlaced->add($ordersPlaced);
            $ordersPlaced->addItem($this);
        }

        return $this;
    }

    public function removeOrdersPlaced(Order $ordersPlaced): self
    {
        if ($this->ordersPlaced->removeElement($ordersPlaced)) {
            $ordersPlaced->removeItem($this);
        }

        return $this;
    }
}
