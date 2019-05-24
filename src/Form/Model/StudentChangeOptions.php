<?php

// src/App/Form/Model/StudentChangeProfile.php
namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class StudentChangeOptions
{
    /**
     * @var string
     */
    private $measureSpeed;

    /**
     * @var boolean
     */
    private $enableSounds;

		/**
     * Set measureSpeed
     *
     * @param string $measureSpeed
     * @return Student
     */
    public function setMeasureSpeed($measureSpeed)
    {
        $this->measureSpeed = $measureSpeed;

        return $this;
    }

    /**
     * Get measureSpeed
     *
     * @return string
     */
    public function getMeasureSpeed()
    {
        return $this->measureSpeed;
    }

    /**
     * Set enableSounds
     *
     * @param boolean $enableSounds
     * @return Student
     */
    public function setEnableSounds($enableSounds)
    {
        $this->enableSounds = $enableSounds;

        return $this;
    }

    /**
     * Get enableSounds
     *
     * @return boolean
     */
    public function getEnableSounds()
    {
        return $this->enableSounds;
    }
}
