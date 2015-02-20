<?php

namespace Tisseo\DatawarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GridCalendar
 */
class GridCalendar
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $monday;

    /**
     * @var boolean
     */
    private $tuesday;

    /**
     * @var boolean
     */
    private $wednesday;

    /**
     * @var boolean
     */
    private $thursday;

    /**
     * @var boolean
     */
    private $friday;

    /**
     * @var boolean
     */
    private $saturday;

    /**
     * @var boolean
     */
    private $sunday;

    /**
     * @var \Tisseo\DatawarehouseBundle\Entity\LineVersion
     */
    private $lineVersion;

    public function merge(GridCalendar $gridCalendar)
    {
        $this->name = $gridCalendar->getName();
        $this->color = $gridCalendar->getColor();
        $this->monday = $gridCalendar->getMonday();
        $this->tuesday = $gridCalendar->getTuesday();
        $this->wednesday = $gridCalendar->getWednesday();
        $this->thursday = $gridCalendar->getThursday();
        $this->friday = $gridCalendar->getFriday();
        $this->saturday = $gridCalendar->getSaturday();
        $this->sunday = $gridCalendar->getSunday();
        $this->lineVersion = $gridCalendar->getLineVersion();
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
     * Set color
     *
     * @param string $color
     * @return GridCalendar
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GridCalendar
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
     * Set monday
     *
     * @param boolean $monday
     * @return GridCalendar
     */
    public function setMonday($monday)
    {
        $this->monday = $monday;

        return $this;
    }

    /**
     * Get monday
     *
     * @return boolean 
     */
    public function getMonday()
    {
        return $this->monday;
    }

    /**
     * Set tuesday
     *
     * @param boolean $tuesday
     * @return GridCalendar
     */
    public function setTuesday($tuesday)
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    /**
     * Get tuesday
     *
     * @return boolean 
     */
    public function getTuesday()
    {
        return $this->tuesday;
    }

    /**
     * Set wednesday
     *
     * @param boolean $wednesday
     * @return GridCalendar
     */
    public function setWednesday($wednesday)
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    /**
     * Get wednesday
     *
     * @return boolean 
     */
    public function getWednesday()
    {
        return $this->wednesday;
    }

    /**
     * Set thursday
     *
     * @param boolean $thursday
     * @return GridCalendar
     */
    public function setThursday($thursday)
    {
        $this->thursday = $thursday;

        return $this;
    }

    /**
     * Get thursday
     *
     * @return boolean 
     */
    public function getThursday()
    {
        return $this->thursday;
    }

    /**
     * Set friday
     *
     * @param boolean $friday
     * @return GridCalendar
     */
    public function setFriday($friday)
    {
        $this->friday = $friday;

        return $this;
    }

    /**
     * Get friday
     *
     * @return boolean 
     */
    public function getFriday()
    {
        return $this->friday;
    }

    /**
     * Set saturday
     *
     * @param boolean $saturday
     * @return GridCalendar
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;

        return $this;
    }

    /**
     * Get saturday
     *
     * @return boolean 
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * Set sunday
     *
     * @param boolean $sunday
     * @return GridCalendar
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;

        return $this;
    }

    /**
     * Get sunday
     *
     * @return boolean 
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * Set lineVersion
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\LineVersion $lineVersion
     * @return GridCalendar
     */
    public function setLineVersion(LineVersion $lineVersion = null)
    {
        $this->lineVersion = $lineVersion;
        $this->lineVersion->addGridCalendar($this);

        return $this;
    }

    /**
     * Get lineVersion
     *
     * @return \Tisseo\DatawarehouseBundle\Entity\LineVersion 
     */
    public function getLineVersion()
    {
        return $this->lineVersion;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $gridLinkCalendarMaskTypes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gridLinkCalendarMaskTypes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gridLinkCalendarMaskTypes
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes
     * @return GridCalendar
     */
    public function addGridLinkCalendarMaskType(\Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes)
    {
        $this->gridLinkCalendarMaskTypes[] = $gridLinkCalendarMaskTypes;

        return $this;
    }

    /**
     * Remove gridLinkCalendarMaskTypes
     *
     * @param \Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes
     */
    public function removeGridLinkCalendarMaskType(\Tisseo\DatawarehouseBundle\Entity\GridLinkCalendarMaskType $gridLinkCalendarMaskTypes)
    {
        $this->gridLinkCalendarMaskTypes->removeElement($gridLinkCalendarMaskTypes);
    }

    /**
     * Get gridLinkCalendarMaskTypes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGridLinkCalendarMaskTypes()
    {
        return $this->gridLinkCalendarMaskTypes;
    }
}
