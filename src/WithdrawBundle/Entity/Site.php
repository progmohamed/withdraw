<?php

namespace WithdrawBundle\Entity;

use AdminBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Site
 *
 * @ORM\Table(name="withdraw_site", indexes={@ORM\Index(name="inx_url", columns={"url"}), @ORM\Index(name="inx_status", columns={"status"}), @ORM\Index(name="inx_user_id", columns={"user_id"}), @ORM\Index(name="inx_created_at", columns={"created_at"}), @ORM\Index(name="inx_updated_at", columns={"updated_at"})})
 * @ORM\Entity(repositoryClass="WithdrawBundle\Entity\Repository\Site\Repository")
 */
class Site
{

    use ORMBehaviors\Timestampable\Timestampable;

    const STATUS_NEW = 1;
    const STATUS_CRAWLING = 2;
    const STATUS_DONE = 3;

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
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     */
    protected $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = self::STATUS_NEW;

    /**
     * @var SiteMetric
     *
     * @ORM\OneToMany(targetEntity="SiteMetric", mappedBy="site", cascade={"all"})
     */
    private $metrics;


    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AdminBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $user;


    public function __construct()
    {
        $this->metrics = new ArrayCollection();
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
     * Set url
     *
     * @param string $url
     *
     * @return Site
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Site
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Get metrics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetrics()
    {
        return $this->metrics;
    }

    /**
     * Add metric
     *
     * @param SiteMetric $metric
     *
     * @return Site
     */
    public function addMetric(SiteMetric $metric)
    {

        $this->metrics->add($metric);
        $metric->setSite($this);

        return $this;
    }

    /**
     * Remove metric
     *
     * @param SiteMetric $metric
     *
     * @return Site
     */
    public function removeMetric(SiteMetric $metric)
    {
        $this->metrics->removeElement($metric);
        $metric->setSite(null);
        return $this;
    }


    /**
     * Set user
     *
     * @param \AdminBundle\Entity\User $user
     *
     * @return Site
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \AdminBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return '' . $this->getUrl();
    }

}
