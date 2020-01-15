<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $locker;

    /**
     * @ORM\Column(type="boolean")
     */
    private $assignedOffice;

    /**
     * @ORM\Column(type="boolean")
     */
    private $coffee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $printer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wireless;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="offer")
     */
    private $booking;

    /**
     * @ORM\Column(type="string")
     */
    private $images;


    public function __construct()
    {
        $this->booking = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getLocker(): ?bool
    {
        return $this->locker;
    }

    public function setLocker(bool $locker): self
    {
        $this->locker = $locker;

        return $this;
    }

    public function getAssignedOffice(): ?bool
    {
        return $this->assignedOffice;
    }

    public function setAssignedOffice(bool $assignedOffice): self
    {
        $this->assignedOffice = $assignedOffice;

        return $this;
    }

    public function getCoffee(): ?bool
    {
        return $this->coffee;
    }

    public function setCoffee(bool $coffee): self
    {
        $this->coffee = $coffee;

        return $this;
    }

    public function getPrinter(): ?bool
    {
        return $this->printer;
    }

    public function setPrinter(bool $printer): self
    {
        $this->printer = $printer;

        return $this;
    }

    public function getWireless(): ?bool
    {
        return $this->wireless;
    }

    public function setWireless(bool $wireless): self
    {
        $this->wireless = $wireless;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }
    public function setImages(string $images): self
    {
        $this->images = $images;
        return $this;
    }
    public function addImages(string $image): self
    {
        $this->images[] = $image;
        return $this;
    }
    
    /**
     * @return Collection|Booking[]
     */
    public function getBooking(): Collection
    {
        return $this->booking;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->booking->contains($booking)) {
            $this->booking[] = $booking;
            $booking->setOffer($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->booking->contains($booking)) {
            $this->booking->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getOffer() === $this) {
                $booking->setOffer(null);
            }
        }

        return $this;
    }

}
