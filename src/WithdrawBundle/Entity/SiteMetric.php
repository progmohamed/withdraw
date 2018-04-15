<?php

namespace WithdrawBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * SiteMetric
 *
 * @ORM\Table(name="withdraw_site_metric", indexes={@ORM\Index(name="inx_metric", columns={"metric"})})
 * @ORM\Entity(repositoryClass="WithdrawBundle\Entity\Repository\SiteMetric\Repository")
 */
class SiteMetric
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
     * @ORM\Column(name="metric", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     */
    protected $metric;


    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", length=65535, nullable=true)
     */
    protected $value = null;


    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="metrics", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $site;


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
     * Set metric
     *
     * @param string $metric
     *
     * @return SiteMetric
     */
    public function setMetric($metric)
    {
        $this->metric = $metric;

        return $this;
    }

    /**
     * Get metric
     *
     * @return string
     */
    public function getMetric()
    {
        return $this->metric;
    }


    /**
     * Set value
     *
     * @param string $value
     *
     * @return SiteMetric
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
     * Set site
     *
     * @param Site $site
     *
     * @return SiteMetric
     */
    public function setSite(Site $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    public function __toString()
    {
        return '' . $this->getUrl();
    }

}
