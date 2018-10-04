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
 * @ORM\Entity(repositoryClass="App\Repository\FlagRepository")
 */
class Flag
{
    /**
     * @ORM\OneToMany(targetEntity="DeviceFlag", mappedBy="flag")
     */
    private $deviceFlags;

    /**
     * @ORM\OneToMany(targetEntity="PossibleNextFlag", mappedBy="parentFlag")
     */
    private $childFlags;

    /**
     * @ORM\OneToMany(targetEntity="PossibleNextFlag", mappedBy="childFlag")
     */
    private $parentFlags;


    public function __construct()
    {
        $this->deviceFlags = new ArrayCollection();
        $this->childFlags = new ArrayCollection();
        $this->parentFlags = new ArrayCollection();
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
        return $this->deviceFlags;
    }

    public function addDeviceFlag(DeviceFlag $deviceFlag): self
    {
        if (!$this->deviceFlags->contains($deviceFlag)) {
            $this->deviceFlags[] = $deviceFlag;
            $deviceFlag->setFlag($this);
        }

        return $this;
    }

    public function removeDeviceFlag(DeviceFlag $deviceFlag): self
    {
        if ($this->deviceFlags->contains($deviceFlag)) {
            $this->deviceFlags->removeElement($deviceFlag);
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
        return $this->childFlags;
    }

    public function addChildFlag(PossibleNextFlag $childFlag): self
    {
        if (!$this->childFlags->contains($childFlag)) {
            $this->childFlags[] = $childFlag;
            $childFlag->setParentFlag($this);
        }

        return $this;
    }

    public function removeChildFlag(PossibleNextFlag $childFlag): self
    {
        if ($this->childFlags->contains($childFlag)) {
            $this->childFlags->removeElement($childFlag);
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
        return $this->parentFlags;
    }

    public function addParentFlag(PossibleNextFlag $parentFlag): self
    {
        if (!$this->parentFlags->contains($parentFlag)) {
            $this->parentFlags[] = $parentFlag;
            $parentFlag->setChildFlag($this);
        }

        return $this;
    }

    public function removeParentFlag(PossibleNextFlag $parentFlag): self
    {
        if ($this->parentFlags->contains($parentFlag)) {
            $this->parentFlags->removeElement($parentFlag);
            // set the owning side to null (unless already changed)
            if ($parentFlag->getChildFlag() === $this) {
                $parentFlag->setChildFlag(null);
            }
        }

        return $this;
    }
}
