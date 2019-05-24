<?php

// src/FrontendBundle/Form/Model/ProfileChange.php
namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class AddAccountFromPartol
{
	/**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Your user name must be at least {{ limit }} characters long",
     *      maxMessage = "Your user name cannot be longer than {{ limit }} characters"
     * )
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 30,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @var \App\Entity\Grade
     *
     */
    private $grade;

    /**
     * Set username
     *
     * @param string $username
     * @return Student
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
     * Set password
     *
     * @param string $password
     * @return Student
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set grade
     *
     * @param \App\Entity\Grade $grade
     * @return GradeStudent
     */
    public function setGrade(\App\Entity\Grade $grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return \App\Entity\Grade 
     */
    public function getGrade()
    {
        return $this->grade;
    }
}