<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TodayTip
 *
 * @ORM\Table(name="today_tip", indexes={@ORM\Index(name="tip", columns={"tip"})})
 * @ORM\Entity
 */
class TodayTip
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="tip", type="integer", nullable=false)
     */
    private $tip;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTip(): ?int
    {
        return $this->tip;
    }

    public function setTip(int $tip): self
    {
        $this->tip = $tip;

        return $this;
    }


}
