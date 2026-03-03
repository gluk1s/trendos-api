<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $is_premium = false;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $country_code = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $last_active_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Device::class)]
    private Collection $devices;

    public function __construct() {
        $this->devices = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        
        return $this;
    }

    public function isPremium(): ?bool
    {
        return $this->is_premium;
    }

    public function setIsPremium(?bool $isPremium): self
    {
        $this->is_premium = $isPremium;
        
        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->country_code = $countryCode;
        
        return $this;
    }

    public function getLastActiveAt(): ?\DateTimeInterface
    {
        return $this->last_active_at;
    }

    public function setLastActiveAt(?\DateTimeInterface $lastActiveAt): self
    {
        $this->last_active_at = $lastActiveAt;
        
        return $this;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
            $device->setUser($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        $this->devices->removeElement($device);

        return $this;
    }
}