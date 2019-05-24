<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 *
 * @ORM\Table(name="banner", indexes={@ORM\Index(name="placing_id", columns={"placing_id"})})
 * @ORM\Entity
 */
class Banner
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
     * @ORM\Column(name="reclam", type="text", length=65535, nullable=false)
     */
    private $reclam;

    /**
     * @var int
     *
     * @ORM\Column(name="viewed", type="integer", nullable=false)
     */
    private $viewed;

    /**
     * @var int
     *
     * @ORM\Column(name="clicked", type="integer", nullable=false)
     */
    private $clicked;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromdate", type="datetime", nullable=false)
     */
    private $fromdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="todate", type="datetime", nullable=false)
     */
    private $todate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date", nullable=false)
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="placing_id", type="string", length=1, nullable=false, options={"fixed"=true,"comment"="Баннер хаана байрлахийг заана."})
     */
    private $placingId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReclam(): ?string
    {
        return $this->reclam;
    }

    public function setReclam(string $reclam): self
    {
        $this->reclam = $reclam;

        return $this;
    }

    public function getViewed(): ?int
    {
        return $this->viewed;
    }

    public function setViewed(int $viewed): self
    {
        $this->viewed = $viewed;

        return $this;
    }

    public function getClicked(): ?int
    {
        return $this->clicked;
    }

    public function setClicked(int $clicked): self
    {
        $this->clicked = $clicked;

        return $this;
    }

    public function getFromdate(): ?\DateTimeInterface
    {
        return $this->fromdate;
    }

    public function setFromdate(\DateTimeInterface $fromdate): self
    {
        $this->fromdate = $fromdate;

        return $this;
    }

    public function getTodate(): ?\DateTimeInterface
    {
        return $this->todate;
    }

    public function setTodate(\DateTimeInterface $todate): self
    {
        $this->todate = $todate;

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

    public function getPlacingId(): ?string
    {
        return $this->placingId;
    }

    public function setPlacingId(string $placingId): self
    {
        $this->placingId = $placingId;

        return $this;
    }


}
