<?php

namespace LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Log
 *
 * @ORM\Table(name="log_log", indexes={@ORM\Index(name="inx_user", columns={"user"}), @ORM\Index(name="inx_created_at", columns={"created_at"}), @ORM\Index(name="inx_updated_at", columns={"updated_at"}), @ORM\Index(name="inx_log_service_id", columns={"log_service_id"}), @ORM\Index(name="inx_username", columns={"username"}) })
 * @ORM\Entity(repositoryClass="LogBundle\Entity\Repository\Log\Repository")
 */
class Log
{

    use ORMBehaviors\Timestampable\Timestampable;

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
     * @ORM\Column(name="message", type="text", length=65535, nullable=false)
     *
     * @Assert\NotBlank()
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(name="parameter", type="text", length=65535, nullable=true)
     */
    protected $parameter;


    /**
     * @var integer
     *
     * @ORM\Column(name="user", type="integer", nullable=true)
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;


    /**
     * @var \LogService
     *
     * @ORM\ManyToOne(targetEntity="LogService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="log_service_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $logService;

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
     * Set message
     *
     * @param string $message
     *
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Set parameter
     *
     * @param string $parameter
     *
     * @return Log
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get parameter
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }


    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Log
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set username
     *
     * @param string $username
     *
     * @return Log
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set logService
     *
     * @param \LogBundle\Entity\LogService $logService
     *
     * @return Log
     */
    public function setLogService(\LogBundle\Entity\LogService $logService = null)
    {
        $this->logService = $logService;

        return $this;
    }

    /**
     * Get logService
     *
     * @return \LogBundle\Entity\LogService
     */
    public function getLogService()
    {
        return $this->logService;
    }


    public function __toString()
    {
        return '' . $this->getMessage();
    }

}
