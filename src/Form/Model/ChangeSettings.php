<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ChangeSettings
{
    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    private $scoreboard;

    /**
     * Set scoreboard
     *
     * @param boolean $scoreboard
     * @return Teacher
     */
    public function setScoreboard($scoreboard)
    {
        $this->scoreboard = $scoreboard;

        return $this;
    }

    /**
     * Get scoreboard
     *
     * @return boolean 
     */
    public function getScoreboard()
    {
        return $this->scoreboard;
    }
}