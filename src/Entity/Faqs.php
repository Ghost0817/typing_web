<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faqs
 *
 * @ORM\Table(name="faqs")
 * @ORM\Entity
 */
class Faqs
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
     * @var int
     *
     * @ORM\Column(name="sortnum", type="integer", nullable=false)
     */
    private $sortnum;

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

    public function getSortnum(): ?int
    {
        return $this->sortnum;
    }

    public function setSortnum(int $sortnum): self
    {
        $this->sortnum = $sortnum;

        return $this;
    }


}
