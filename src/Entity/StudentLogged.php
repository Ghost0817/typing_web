<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudentLogged
 *
 * @ORM\Table(name="student_logged", indexes={@ORM\Index(name="student_id", columns={"student_id"})})
 * @ORM\Entity
 */
class StudentLogged
{
    /**
     * @var string
     *
     * @ORM\Column(name="sess_id", type="string", length=128, nullable=false)
     * @ORM\Id
     */
    private $sessId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sess_date", type="date", nullable=false)
     */
    private $sessDate;

    /**
     * @var \Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;

    public function getSessId(): ?string
    {
        return $this->sessId;
    }

    public function setSessId(string $sessId): self
    {
        $this->sessId = $sessId;
        return $this;
    }

    public function getSessDate(): ?\DateTimeInterface
    {
        return $this->sessDate;
    }

    public function setSessDate(\DateTimeInterface $sessDate): self
    {
        $this->sessDate = $sessDate;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }


}
