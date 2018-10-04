<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

 /**
 * App\Entity\DeviceFlag
 *
 * @ORM\Table(name="device_flag", options={"engine": "InnoDB"})
 * @ORM\Entity(repositoryClass="App\Entity\DeviceFlagRepository")
 */
class DeviceFlag
{
    /**
      * @ORM\ManyToOne(targetEntity="Device", inversedBy="flags", cascade={"persist"})
      * @ORM\JoinColumn(name="serial_number", referencedColumnName="serialNumber")
      */
    protected $device;

    /**
      * @ORM\ManyToOne(targetEntity="Flag", inversedBy="deviceFlags", cascade={"persist"})
      * @ORM\JoinColumn(name="flag_id", referencedColumnName="id")
      */
    protected $flag;

    public function __construct()
    {

    }

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

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

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getFlag(): ?Flag
    {
        return $this->flag;
    }

    public function setFlag(?Flag $flag): self
    {
        $this->flag = $flag;

        return $this;
    }
}
