<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson", indexes={@ORM\Index(name="cate", columns={"cate"})})
 * @ORM\Entity(repositoryClass="App\Repository\LessonRepository")
 */
class Lesson
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
     * @ORM\Column(name="mn_intro", type="text", length=65535, nullable=false)
     */
    private $mnIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="en_intro", type="text", length=65535, nullable=false)
     */
    private $enIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="key_layout", type="string", length=10, nullable=false)
     */
    private $keyLayout;

    /**
     * @var int
     *
     * @ORM\Column(name="sortnum", type="integer", nullable=false)
     */
    private $sortnum;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_premium", type="boolean", nullable=false)
     */
    private $isPremium;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false, options={"comment"="Харагдах эсхийг шийдэнэ."})
     */
    private $isShow;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsShowKeys", type="boolean", nullable=false)
     */
    private $isshowkeys;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cate", referencedColumnName="id")
     * })
     */
    private $cate;

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

    public function getMnIntro(): ?string
    {
        return $this->mnIntro;
    }

    public function setMnIntro(string $mnIntro): self
    {
        $this->mnIntro = $mnIntro;

        return $this;
    }

    public function getEnIntro(): ?string
    {
        return $this->enIntro;
    }

    public function setEnIntro(string $enIntro): self
    {
        $this->enIntro = $enIntro;

        return $this;
    }

    public function getKeyLayout(): ?string
    {
        return $this->keyLayout;
    }

    public function setKeyLayout(string $keyLayout): self
    {
        $this->keyLayout = $keyLayout;

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

    public function getIsPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(bool $isPremium): self
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    public function getIsShow(): ?bool
    {
        return $this->isShow;
    }

    public function setIsShow(bool $isShow): self
    {
        $this->isShow = $isShow;

        return $this;
    }

    public function getIsshowkeys(): ?bool
    {
        return $this->isshowkeys;
    }

    public function setIsshowkeys(bool $isshowkeys): self
    {
        $this->isshowkeys = $isshowkeys;

        return $this;
    }

    public function getCate(): ?Category
    {
        return $this->cate;
    }

    public function setCate(?Category $cate): self
    {
        $this->cate = $cate;

        return $this;
    }


}
