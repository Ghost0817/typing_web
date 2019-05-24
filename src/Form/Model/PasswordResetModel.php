<?php

// src/App/Form/Model/PasswordResetModel.php
namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordResetModel
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 7,
     *      max = 30,
     *      minMessage = "Your email must be at least {{ limit }} characters long",
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;


    /**
     * Set password
     *
     * @param string $password
     *
     * @return Admin
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
}
