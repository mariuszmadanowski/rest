<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

 /**
 * App\Entity\PossibleNextFlag
 *
 * @ORM\Table(name="possible_next_flag", options={"engine": "InnoDB"})
 * @ORM\Entity(repositoryClass="App\Entity\PossibleNextFlagRepository")
 */
class PossibleNextFlag
{
    /**
      * @ORM\ManyToOne(targetEntity="Flag", inversedBy="childFlags", cascade={"persist"})
      * @ORM\JoinColumn(name="parent_flag_id", referencedColumnName="id")
      */
    protected $parentFlag;

    /**
      * @ORM\ManyToOne(targetEntity="Flag", inversedBy="parentFlags", cascade={"persist"})
      * @ORM\JoinColumn(name="child_flag_id", referencedColumnName="id")
      */
    protected $childFlag;


    public function __construct()
    {

    }

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentFlag(): ?Flag
    {
        return $this->parentFlag;
    }

    public function setParentFlag(?Flag $parentFlag): self
    {
        $this->parentFlag = $parentFlag;

        return $this;
    }

    public function getChildFlag(): ?Flag
    {
        return $this->childFlag;
    }

    public function setChildFlag(?Flag $childFlag): self
    {
        $this->childFlag = $childFlag;

        return $this;
    }
}
