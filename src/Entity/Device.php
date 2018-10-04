<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

 /**
 * App\Entity\Device
 *
 * @ORM\Table(name="device", options={"engine": "InnoDB"})
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @ORM\OneToMany(targetEntity="DeviceFlag", mappedBy="device")
     */
    private $flags;


    public function __construct()
    {
        $this->flags = new ArrayCollection();
    }

    /**
     * @ORM\Column(name="serial_number", type="string", length=10, unique=true)
     * @ORM\Id
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern = "/[a-z0-9]+/",
     *     message = "Invalid serial number. The serial number may only contain lowercase letters and numbers."
     * )
     */
    private $serialNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|DeviceFlag[]
     */
    public function getFlags(): Collection
    {
        return $this->flags;
    }

    public function addFlag(DeviceFlag $flag): self
    {
        if (!$this->flags->contains($flag)) {
            $this->flags[] = $flag;
            $flag->setDevice($this);
        }

        return $this;
    }

    public function removeFlag(DeviceFlag $flag): self
    {
        if ($this->flags->contains($flag)) {
            $this->flags->removeElement($flag);
            // set the owning side to null (unless already changed)
            if ($flag->getDevice() === $this) {
                $flag->setDevice(null);
            }
        }

        return $this;
    }
}
