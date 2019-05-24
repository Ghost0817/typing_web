<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity
 */
class Game
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
     * @ORM\Column(name="mn_discription", type="string", length=255, nullable=false)
     */
    private $mnDiscription;

    /**
     * @var string
     *
     * @ORM\Column(name="en_discription", type="string", length=255, nullable=false)
     */
    private $enDiscription;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=false)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=65535, nullable=false)
     */
    private $body;

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

    public function getMnDiscription(): ?string
    {
        return $this->mnDiscription;
    }

    public function setMnDiscription(string $mnDiscription): self
    {
        $this->mnDiscription = $mnDiscription;

        return $this;
    }

    public function getEnDiscription(): ?string
    {
        return $this->enDiscription;
    }

    public function setEnDiscription(string $enDiscription): self
    {
        $this->enDiscription = $enDiscription;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }


}
