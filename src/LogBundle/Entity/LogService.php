<?php

namespace LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * LogService
 *
 * @ORM\Table(name="log_log_service", uniqueConstraints={@ORM\UniqueConstraint(name="inx_name", columns={"name"})})
 * @ORM\Entity()
 * @UniqueEntity(fields = {"name"},
 *     message="هذا المكون موجود مسبقاا"
 * )
 */
class LogService
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Log", mappedBy="logService")
     */
    private $log;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->log = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return LogService
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get Log
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLog()
    {
        return $this->log;
    }

    public function __toString()
    {
        return '' . $this->getName();
    }

}
