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
      * @ORM\ManyToOne(targetEntity="Flag", inversedBy="child_flags", cascade={"persist"})
      * @ORM\JoinColumn(name="parent_flag_id", referencedColumnName="id")
      */
    protected $parent_flag;

    /**
      * @ORM\ManyToOne(targetEntity="Flag", inversedBy="parent_flags", cascade={"persist"})
      * @ORM\JoinColumn(name="child_flag_id", referencedColumnName="id")
      */
    protected $child_flag;


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
        return $this->parent_flag;
    }

    public function setParentFlag(?Flag $parent_flag): self
    {
        $this->parent_flag = $parent_flag;

        return $this;
    }

    public function getChildFlag(): ?Flag
    {
        return $this->child_flag;
    }

    public function setChildFlag(?Flag $child_flag): self
    {
        $this->child_flag = $child_flag;

        return $this;
    }
}
