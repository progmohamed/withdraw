<?php

namespace ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ConfigUserVariable
 *
 * @ORM\Table(name="config_user_variable", uniqueConstraints={@ORM\UniqueConstraint(name="inx_user_config_variable_id", columns={"config_variable_id", "user"})}, indexes={@ORM\Index(name="inx_config_variable_id", columns={"config_variable_id"})})
 * @ORM\Entity(repositoryClass="ConfigBundle\Entity\Repository\ConfigUserVariable\Repository")
 * @UniqueEntity(fields = {"config_variable_id", "user"},
 *     message="ا يمكن تكرار متغير واحد لنفس المستخدم"
 * )
 */
class ConfigUserVariable
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
     * @var integer
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;


    /**
     * @var integer
     *
     * @ORM\Column(name="user", type="integer", nullable=false)
     */
    private $user;


    /**
     * @var \ConfigVariable
     *
     * @ORM\ManyToOne(targetEntity="ConfigBundle\Entity\ConfigVariable")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="config_variable_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $configVariable;


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
     * Set value
     *
     * @param string $value
     *
     * @return ConfigUserVariable
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return ConfigUserVariable
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
     * Set configVariable
     *
     * @param \ConfigBundle\Entity\ConfigVariable $configVariable
     *
     * @return ConfigUserVariable
     */
    public function setConfigVariable(\ConfigBundle\Entity\ConfigVariable $configVariable = null)
    {
        $this->configVariable = $configVariable;

        return $this;
    }

    /**
     * Get configVariable
     *
     * @return \ConfigBundle\Entity\ConfigVariable
     */
    public function getConfigVariable()
    {
        return $this->configVariable;
    }


    public function __toString()
    {
        if ($variable = $this->getConfigVariable()->getVariableTranslation()) {
            return $variable;
        }

        return '';
    }
}
