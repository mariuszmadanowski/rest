<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

 /**
 * App\Entity\Flag
 *
 * @ORM\Table(name="flag", options={"engine": "InnoDB"})
 * @ORM\Entity(repositoryClass="App\Entity\FlagRepository")
 */
class Flag
{
    /**
     * @ORM\OneToMany(targetEntity="DeviceFlag", mappedBy="flag")
     */
    private $device_flags;

    /**
     * @ORM\OneToMany(targetEntity="PossibleNextFlag", mappedBy="parent_flag")
     */
    private $child_flags;

    /**
     * @ORM\OneToMany(targetEntity="PossibleNextFlag", mappedBy="child_flag")
     */
    private $parent_flags;


    public function __construct()
    {
        $this->device_flags = new ArrayCollection();
        $this->child_flags = new ArrayCollection();
        $this->parent_flags = new ArrayCollection();
    }

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $name;

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

    /**
     * @return Collection|DeviceFlag[]
     */
    public function getDeviceFlags(): Collection
    {
        return $this->device_flags;
    }

    public function addDeviceFlag(DeviceFlag $deviceFlag): self
    {
        if (!$this->device_flags->contains($deviceFlag)) {
            $this->device_flags[] = $deviceFlag;
            $deviceFlag->setFlag($this);
        }

        return $this;
    }

    public function removeDeviceFlag(DeviceFlag $deviceFlag): self
    {
        if ($this->device_flags->contains($deviceFlag)) {
            $this->device_flags->removeElement($deviceFlag);
            // set the owning side to null (unless already changed)
            if ($deviceFlag->getFlag() === $this) {
                $deviceFlag->setFlag(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PossibleNextFlag[]
     */
    public function getChildFlags(): Collection
    {
        return $this->child_flags;
    }

    public function addChildFlag(PossibleNextFlag $childFlag): self
    {
        if (!$this->child_flags->contains($childFlag)) {
            $this->child_flags[] = $childFlag;
            $childFlag->setParentFlag($this);
        }

        return $this;
    }

    public function removeChildFlag(PossibleNextFlag $childFlag): self
    {
        if ($this->child_flags->contains($childFlag)) {
            $this->child_flags->removeElement($childFlag);
            // set the owning side to null (unless already changed)
            if ($childFlag->getParentFlag() === $this) {
                $childFlag->setParentFlag(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PossibleNextFlag[]
     */
    public function getParentFlags(): Collection
    {
        return $this->parent_flags;
    }

    public function addParentFlag(PossibleNextFlag $parentFlag): self
    {
        if (!$this->parent_flags->contains($parentFlag)) {
            $this->parent_flags[] = $parentFlag;
            $parentFlag->setChildFlag($this);
        }

        return $this;
    }

    public function removeParentFlag(PossibleNextFlag $parentFlag): self
    {
        if ($this->parent_flags->contains($parentFlag)) {
            $this->parent_flags->removeElement($parentFlag);
            // set the owning side to null (unless already changed)
            if ($parentFlag->getChildFlag() === $this) {
                $parentFlag->setChildFlag(null);
            }
        }

        return $this;
    }
}
