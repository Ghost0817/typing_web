<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mature
 *
 * @ORM\Table(name="mature")
 * @ORM\Entity
 */
class Mature
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=150, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="condition_one", type="string", length=255, nullable=false)
     */
    private $conditionOne;

    /**
     * @var string
     *
     * @ORM\Column(name="condition_two", type="string", length=255, nullable=false)
     */
    private $conditionTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="condition_three", type="string", length=255, nullable=false)
     */
    private $conditionThree;

    /**
     * @var int|null
     *
     * @ORM\Column(name="condition_term", type="integer", nullable=true)
     */
    private $conditionTerm;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=150, nullable=false)
     */
    private $role;

    /**
     * @var bool
     *
     * @ORM\Column(name="featured", type="boolean", nullable=false)
     */
    private $featured;

    /**
     * @var int
     *
     * @ORM\Column(name="softnum", type="integer", nullable=false)
     */
    private $softnum;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getConditionOne(): ?string
    {
        return $this->conditionOne;
    }

    public function setConditionOne(string $conditionOne): self
    {
        $this->conditionOne = $conditionOne;

        return $this;
    }

    public function getConditionTwo(): ?string
    {
        return $this->conditionTwo;
    }

    public function setConditionTwo(string $conditionTwo): self
    {
        $this->conditionTwo = $conditionTwo;

        return $this;
    }

    public function getConditionThree(): ?string
    {
        return $this->conditionThree;
    }

    public function setConditionThree(string $conditionThree): self
    {
        $this->conditionThree = $conditionThree;

        return $this;
    }

    public function getConditionTerm(): ?int
    {
        return $this->conditionTerm;
    }

    public function setConditionTerm(?int $conditionTerm): self
    {
        $this->conditionTerm = $conditionTerm;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function getSoftnum(): ?int
    {
        return $this->softnum;
    }

    public function setSoftnum(int $softnum): self
    {
        $this->softnum = $softnum;

        return $this;
    }


}
