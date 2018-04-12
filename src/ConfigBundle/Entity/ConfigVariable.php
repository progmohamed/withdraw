<?php

namespace ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ConfigVariable
 *
 * @ORM\Table(name="config_variable", uniqueConstraints={@ORM\UniqueConstraint(name="inx_variable", columns={"variable"})}, indexes={@ORM\Index(name="inx_type", columns={"type"}), @ORM\Index(name="inx_type", columns={"type"}),@ORM\Index(name="inx_variable_translation", columns={"variable_translation"}), @ORM\Index(name="inx_section_translation", columns={"section_translation"}), @ORM\Index(name="inx_sort_order", columns={"sort_order"}), @ORM\Index(name="inx_scope", columns={"scope"})})
 * @ORM\Entity(repositoryClass="ConfigBundle\Entity\Repository\ConfigVariable\Repository")
 * @UniqueEntity("variable")
 */
class ConfigVariable
{
    const TYPE_NUMERIC = 1;
    const TYPE_STRING = 2;
    const TYPE_TEXT = 3;
    const TYPE_CHOICE = 4;
    const TYPE_BOOLEAN = 8;

    const SCOPE_GLOBAL = 1;
    const SCOPE_OVERRIDABLE = 2;
    const SCOPE_USER = 3;


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
     * @ORM\Column(name="variable_translation", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $variableTranslation;

    /**
     * @var string
     *
     * @ORM\Column(name="variable_translation_variable", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $variableTranslationVariable;

    /**
     * @var string
     *
     * @ORM\Column(name="section_translation", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $sectionTranslation;


    /**
     * @var string
     *
     * @ORM\Column(name="variable", type="string", length=255, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    private $variable;

    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $value;


    /**
     * @var integer
     *
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     */
    private $sortOrder;


    /**
     * @var integer
     *
     * @ORM\Column(name="scope", type="integer", nullable=false)
     */
    private $scope = self::SCOPE_GLOBAL;



    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ConfigUserVariable", mappedBy="configVariable")
     */
    private $configUserVariable;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configUserVariable = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set variable
     *
     * @param string $variable
     *
     * @return ConfigVariable
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * Get variable
     *
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * Set variableTranslation
     *
     * @param string $variableTranslation
     *
     * @return ConfigVariable
     */
    public function setVariableTranslation($variableTranslation)
    {
        $this->variableTranslation = $variableTranslation;

        return $this;
    }

    /**
     * Get variableTranslation
     *
     * @return string
     */
    public function getVariableTranslation()
    {
        return $this->variableTranslation;
    }

    /**
     * Set variableTranslationVariable
     *
     * @param string $variableTranslationVariable
     *
     * @return ConfigVariable
     */
    public function setVariableTranslationVariable($variableTranslationVariable)
    {
        $this->variableTranslationVariable = $variableTranslationVariable;

        return $this;
    }

    /**
     * Get variableTranslationVariable
     *
     * @return string
     */
    public function getVariableTranslationVariable()
    {
        return $this->variableTranslationVariable;
    }

    /**
     * Set sectionTranslation
     *
     * @param string $sectionTranslation
     *
     * @return ConfigVariable
     */
    public function setSectionTranslation($sectionTranslation)
    {
        $this->sectionTranslation = $sectionTranslation;

        return $this;
    }

    /**
     * Get sectionTranslation
     *
     * @return string
     */
    public function getSectionTranslation()
    {
        return $this->sectionTranslation;
    }


    /**
     * Set value
     *
     * @param string $value
     *
     * @return ConfigVariable
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
     * Set data
     *
     * @param string $data
     *
     * @return ConfigVariable
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Set type
     *
     * @param integer $type
     *
     * @return ConfigVariable
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return ConfigVariable
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set scope
     *
     * @param integer $scope
     *
     * @return ConfigVariable
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return integer
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * Get configUserVariable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfigUserVariable()
    {
        return $this->configUserVariable;
    }


    public function __toString()
    {
        if ($variable = $this->getVariableTranslation()) {
            return $variable;
        }

        return '';
    }
}
