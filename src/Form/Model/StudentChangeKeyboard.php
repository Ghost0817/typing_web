<?php

// src/App/Form/Model/StudentChangeProfile.php
namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class StudentChangeKeyboard
{
    /**
     * @var \FrontendBundle\Entity\MyKeybroard
     */
    private $mykb;

    /**
     * Set mykb
     *
     * @param \App\Entity\MyKeybroard $mykb
     * @return Student
     */
    public function setMykb(\App\Entity\MyKeybroard $mykb = null)
    {
        $this->mykb = $mykb;

        return $this;
    }

    /**
     * Get mykb
     *
     * @return \App\Entity\MyKeybroard
     */
    public function getMykb()
    {
        return $this->mykb;
    }
}
