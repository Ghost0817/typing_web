<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Blog
 *
 * @ORM\Table(name="blog", uniqueConstraints={@ORM\UniqueConstraint(name="slug", columns={"slug"})}, indexes={@ORM\Index(name="user", columns={"user"})})
 * @ORM\Entity(repositoryClass="App\Repository\BlogRepository")
 */
class Blog
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
     * @ORM\Column(name="mn_discription", type="text", length=65535, nullable=false)
     */
    private $mnDiscription;

    /**
     * @var string
     *
     * @ORM\Column(name="en_discription", type="text", length=65535, nullable=false)
     */
    private $enDiscription;

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

    /**
     * @var bool
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false)
     */
    private $isShow;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_en", type="string", length=255, nullable=false)
     */
    private $imageEn;

    /**
     * @var int
     *
     * @ORM\Column(name="mwidth", type="integer", nullable=false)
     */
    private $mwidth;

    /**
     * @var int
     *
     * @ORM\Column(name="mheight", type="integer", nullable=false)
     */
    private $mheight;

    /**
     * @var \Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tag")
     * @ORM\JoinTable(name="blogandtag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tag;


    public function __construct()
    {
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getIsShow(): ?bool
    {
        return $this->isShow;
    }

    public function setIsShow(bool $isShow): self
    {
        $this->isShow = $isShow;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageEn(): ?string
    {
        return $this->imageEn;
    }

    public function setImageEn(string $imageEn): self
    {
        $this->imageEn = $imageEn;

        return $this;
    }

    public function getMwidth(): ?int
    {
        return $this->mwidth;
    }

    public function setMwidth(int $mwidth): self
    {
        $this->mwidth = $mwidth;

        return $this;
    }

    public function getMheight(): ?int
    {
        return $this->mheight;
    }

    public function setMheight(int $mheight): self
    {
        $this->mheight = $mheight;

        return $this;
    }

    public function getUser(): ?Admin
    {
        return $this->user;
    }

    public function setUser(?Admin $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }
}
