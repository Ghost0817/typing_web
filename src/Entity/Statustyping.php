<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statustyping
 *
 * @ORM\Table(name="statustyping", indexes={@ORM\Index(name="fk_statustyping_exercise1_idx", columns={"exercise_id"}), @ORM\Index(name="student_id", columns={"student_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\StatustypingRepository")
 */
class Statustyping
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
     * @var int
     *
     * @ORM\Column(name="time", type="integer", nullable=false)
     */
    private $time;

    /**
     * @var float
     *
     * @ORM\Column(name="acc", type="float", precision=10, scale=0, nullable=false)
     */
    private $acc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="problemkey", type="string", length=255, nullable=true)
     */
    private $problemkey;

    /**
     * @var int
     *
     * @ORM\Column(name="correcthit", type="integer", nullable=false)
     */
    private $correcthit;

    /**
     * @var int
     *
     * @ORM\Column(name="mistakehit", type="integer", nullable=false)
     */
    private $mistakehit;

    /**
     * @var string
     *
     * @ORM\Column(name="error_keys", type="text", length=65535, nullable=false)
     */
    private $errorKeys;

    /**
     * @var string
     *
     * @ORM\Column(name="all_keys", type="text", length=65535, nullable=false)
     */
    private $allKeys;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=false)
     */
    private $lastlogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifier_at", type="datetime", nullable=false)
     */
    private $modifierAt;

    /**
     * @var \Exercise
     *
     * @ORM\ManyToOne(targetEntity="Exercise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_id", referencedColumnName="id")
     * })
     */
    private $exercise;

    /**
     * @var \MyKeybroard
     *
     * @ORM\ManyToOne(targetEntity="MyKeybroard")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="keyboard", referencedColumnName="id")
     * })
     */
    private $keyboard;

    /**
     * @var \StudentLogged
     *
     * @ORM\ManyToOne(targetEntity="StudentLogged")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sess_id", referencedColumnName="sess_id")
     * })
     */
    private $sess;

    /**
     * @var \Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getAcc(): ?float
    {
        return $this->acc;
    }

    public function setAcc(float $acc): self
    {
        $this->acc = $acc;

        return $this;
    }

    public function getProblemkey(): ?string
    {
        return $this->problemkey;
    }

    public function setProblemkey(?string $problemkey): self
    {
        $this->problemkey = $problemkey;

        return $this;
    }

    public function getCorrecthit(): ?int
    {
        return $this->correcthit;
    }

    public function setCorrecthit(int $correcthit): self
    {
        $this->correcthit = $correcthit;

        return $this;
    }

    public function getMistakehit(): ?int
    {
        return $this->mistakehit;
    }

    public function setMistakehit(int $mistakehit): self
    {
        $this->mistakehit = $mistakehit;

        return $this;
    }

    public function getErrorKeys(): ?string
    {
        return $this->errorKeys;
    }

    public function setErrorKeys(string $errorKeys): self
    {
        $this->errorKeys = $errorKeys;

        return $this;
    }

    public function getAllKeys(): ?string
    {
        return $this->allKeys;
    }

    public function setAllKeys(string $allKeys): self
    {
        $this->allKeys = $allKeys;

        return $this;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function setLastlogin(\DateTimeInterface $lastlogin): self
    {
        $this->lastlogin = $lastlogin;

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

    public function getModifierAt(): ?\DateTimeInterface
    {
        return $this->modifierAt;
    }

    public function setModifierAt(\DateTimeInterface $modifierAt): self
    {
        $this->modifierAt = $modifierAt;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getKeyboard(): ?MyKeybroard
    {
        return $this->keyboard;
    }

    public function setKeyboard(?MyKeybroard $keyboard): self
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    public function getSess(): ?StudentLogged
    {
        return $this->sess;
    }

    public function setSess(?StudentLogged $sess): self
    {
        $this->sess = $sess;

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
