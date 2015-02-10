<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Collections\Collection;

/**
 * Line
 */
class Line
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\PhysicalMode
     */
    private $physicalMode;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lineDatasources;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lineVersions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lineDatasources = new ArrayCollection();
        $this->lineVersions = new ArrayCollection();
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
     * Set number
     *
     * @param string $number
     * @return Line
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set physicalMode
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\PhysicalMode $physicalMode
     * @return Line
     */
    public function setPhysicalMode(PhysicalMode $physicalMode = null)
    {
        $this->physicalMode = $physicalMode;

        return $this;
    }

    /**
     * Get physicalMode
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\PhysicalMode 
     */
    public function getPhysicalMode()
    {
        return $this->physicalMode;
    }

    /**
     * Get physicalMode
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\PhysicalMode 
     */
    public function getPhysicalModeId()
    {
        return $this->physicalMode->getId();
    }

    /**
     * Add lineDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineDatasource $lineDatasources
     * @return Line
     */
    public function addLineDatasources(LineDatasource $lineDatasources)
    {
        $this->lineDatasources[] = $lineDatasources;
        $lineDatasources->setLine($this);
        return $this;
    }

    /**
     * Set lineDatasources
     *
     * @param \Doctrine\Common\Collections\Collection $lineDatasources
     * @return Line
     */
    public function setLineDatasources(Collection $lineDatasources)
    {
        $this->lineDatasources = $lineDatasources;
        foreach ($this->lineDatasources as $lineDatasource) {
            $lineDatasource->setLine($this);
        }
        return $this;
    }

    /**
     * Remove lineDatasources
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineDatasource $lineDatasources
     */
    public function removeLineDatasources(LineDatasource $lineDatasources)
    {
        $this->lineDatasources->removeElement($lineDatasources);
    }

    /**
     * Get lineDatasources
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLineDatasources()
    {
        return $this->lineDatasources;
    }

    /**
     * Add lineVersions
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersions
     * @return Line
     */
    public function addLineVersions(LineVersion $lineVersions)
    {
        $this->lineVersions[] = $lineVersions;
        $lineVersions->setLine($this);
        return $this;
    }

    /**
     * Set lineVersions
     *
     * @param \Doctrine\Common\Collections\Collection $lineVersions
     * @return Line
     */
    public function setLineVersions(Collection $lineVersions)
    {
        $this->lineVersions = $lineVersions;
        foreach ($this->lineVersions as $lineVersion) {
            $lineVersion->setLine($this);
        }
        return $this;
    }

    /**
     * Remove lineVersions 
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersions
     */
    public function removeLineVersions(LineVersion $lineVersions)
    {
        $this->lineVersions->removeElement($lineVersions);
    }

    /**
     * Get lineVersions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLineVersions()
    {
        return $this->lineVersions;
    }

    /**
     * Get lastVersionOfLineVersions
     *
     * @return integer
     */
    public function getLastVersionOfLineVersions()
    {
        foreach($this->lineVersions as $lineVersion)
        {
            if ($lineVersion->getEndDate() === null)
                return $lineVersion->getVersion();
        }
        return 0;
    }

    public function getLastLineVersion()
    {
        foreach($this->lineVersions as $lineVersion)
        {
            if ($lineVersion->getEndDate() === null)
                return $lineVersion;
        }
        return null;
    }
}