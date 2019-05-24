<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PasswordReset
 *
 * @ORM\Table(name="password_reset", indexes={@ORM\Index(name="student_id", columns={"student_id"})})
 * @ORM\Entity
 */
class PasswordReset
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="activationKey", type="string", length=255, nullable=false, options={"comment"="Нууц үг сэргээх нууц код байна."})
     */
    private $activationkey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"comment"="Үүсгэсэн огноо"})
     *
     */
    private $createdAt;

    /**
     * @var \Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     * @Assert\IsNull
     */
    private $student;

    /**
     * @var \Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     * @Assert\IsNull
     */
    private $teacher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivationkey(): ?string
    {
        if (null === $this->activationkey) {
            if(null !== $this->teacher) {
                $this->activationkey = md5(sprintf(
                    '%s_%d_%s_%f_%s_%d',
                    uniqid(),
                    rand(0, 99999),
                    $this->teacher->getUsername(),
                    microtime(true),
                    $this->teacher->getEmail(),
                    rand(99999, 999999)
                ));
            }
            if(null !== $this->student) {
                $this->activationkey = md5(sprintf(
                    '%s_%d_%s_%f_%s_%d',
                    uniqid(),
                    rand(0, 99999),
                    $this->student->getUsername(),
                    microtime(true),
                    $this->student->getEmail(),
                    rand(99999, 999999)
                ));
            }
        }
        return $this->activationkey;
    }

    public function setActivationkey(string $activationkey): self
    {
        $this->activationkey = $activationkey;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }
}
