<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exercise
 *
 * @ORM\Table(name="exercise", indexes={@ORM\Index(name="lesson", columns={"lesson"})})
 * @ORM\Entity
 */
class Exercise
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
     * @ORM\Column(name="mn_title", type="string", length=255, nullable=false)
     */
    private $mnTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="en_title", type="string", length=255, nullable=false)
     */
    private $enTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="mn_body", type="text", length=65535, nullable=false)
     */
    private $mnBody;

    /**
     * @var string
     *
     * @ORM\Column(name="en_body", type="text", length=65535, nullable=false)
     */
    private $enBody;

    /**
     * @var string
     *
     * @ORM\Column(name="tutor", type="text", length=65535, nullable=false)
     */
    private $tutor;

    /**
     * @var int
     *
     * @ORM\Column(name="examtime", type="integer", nullable=false, options={"comment"="Хэрвээ шалгалт биш бол 0 байна. Шалгал гэж бодож байвал шалгалт авах хугацаагаа оруулна."})
     */
    private $examtime;

    /**
     * @var int
     *
     * @ORM\Column(name="PermissiblePercent", type="integer", nullable=false)
     */
    private $permissiblepercent;

    /**
     * @var int
     *
     * @ORM\Column(name="sortnum", type="integer", nullable=false)
     */
    private $sortnum;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_premiun", type="boolean", nullable=false)
     */
    private $isPremiun;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \Lesson
     *
     * @ORM\ManyToOne(targetEntity="Lesson")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lesson", referencedColumnName="id")
     * })
     */
    private $lesson;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMnTitle(): ?string
    {
        return $this->mnTitle;
    }

    public function setMnTitle(string $mnTitle): self
    {
        $this->mnTitle = $mnTitle;

        return $this;
    }

    public function getEnTitle(): ?string
    {
        return $this->enTitle;
    }

    public function setEnTitle(string $enTitle): self
    {
        $this->enTitle = $enTitle;

        return $this;
    }

    public function getMnBody(): ?string
    {
        return $this->mnBody;
    }

    public function setMnBody(string $mnBody): self
    {
        $this->mnBody = $mnBody;

        return $this;
    }

    public function getEnBody(): ?string
    {
        return $this->enBody;
    }

    public function setEnBody(string $enBody): self
    {
        $this->enBody = $enBody;

        return $this;
    }

    public function getTutor(): ?string
    {
        return $this->tutor;
    }

    public function setTutor(string $tutor): self
    {
        $this->tutor = $tutor;

        return $this;
    }

    public function getExamtime(): ?int
    {
        return $this->examtime;
    }

    public function setExamtime(int $examtime): self
    {
        $this->examtime = $examtime;

        return $this;
    }

    public function getPermissiblepercent(): ?int
    {
        return $this->permissiblepercent;
    }

    public function setPermissiblepercent(int $permissiblepercent): self
    {
        $this->permissiblepercent = $permissiblepercent;

        return $this;
    }

    public function getSortnum(): ?int
    {
        return $this->sortnum;
    }

    public function setSortnum(int $sortnum): self
    {
        $this->sortnum = $sortnum;

        return $this;
    }

    public function getIsPremiun(): ?bool
    {
        return $this->isPremiun;
    }

    public function setIsPremiun(bool $isPremiun): self
    {
        $this->isPremiun = $isPremiun;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }


}
