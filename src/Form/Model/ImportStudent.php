<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportStudent
{
	/**
     * @var string
     * @Assert\NotBlank()
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var \App\Entity\Grade
     *
     */
    private $grade;

    /**
     * Set file
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
