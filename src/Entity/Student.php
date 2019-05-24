<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

// DON'T forget this use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Student
 *
 * @ORM\Table(name="student", uniqueConstraints={@ORM\UniqueConstraint(name="email_idx", columns={"email"}), @ORM\UniqueConstraint(name="username_idx", columns={"username"})}, indexes={@ORM\Index(name="mykb", columns={"mykb"}), @ORM\Index(name="role", columns={"role", "mykb"})})
 * @UniqueEntity(fields="username", message="<strong>Error creating student</strong> Username is already in use.<br><br>Bicheech.com uses a shared set of usernames between all accounts. If necessary, we suggest prefixing usernames with something unique such as a school name's acroynm.<br><br>Examples: ucla.{{ value }} or ucla{{ value }}")
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="firstname", type="string", length=55, nullable=true)
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=55, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="salt", type="string", length=100, nullable=true)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="activationKey", type="string", length=100, nullable=false)
     */
    private $activationkey;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="measure_speed", type="string", length=3, nullable=false)
     */
    private $measureSpeed;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable_sounds", type="boolean", nullable=false)
     */
    private $enableSounds;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_action", type="string", length=25, nullable=true)
     */
    private $lastAction;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_activity_at", type="datetime", nullable=true)
     */
    private $lastActivityAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
     */
    private $lastlogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var \MyKeybroard
     *
     * @ORM\ManyToOne(targetEntity="MyKeybroard")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mykb", referencedColumnName="id")
     * })
     */
    private $mykb;

    /**
     * @var int
     *
     * @ORM\Column(name="sentence_spaces", type="integer", nullable=false)
     */
    private $sentenceSpaces;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getActivationkey(): ?string
    {
        if (null === $this->activationkey) {
            $this->activationkey = md5(sprintf(
                '%s_%d_%s_%f_%s_%d',
                uniqid(),
                rand(0, 99999),
                $this->getUsername(),
                microtime(true),
                $this->getEmail(),
                rand(99999, 999999)
            ));
        }
        return $this->activationkey;
    }

    public function setActivationkey(string $activationkey): self
    {
        $this->activationkey = $activationkey;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getMeasureSpeed(): ?string
    {
        return $this->measureSpeed;
    }

    public function setMeasureSpeed(string $measureSpeed): self
    {
        $this->measureSpeed = $measureSpeed;

        return $this;
    }

    public function getEnableSounds(): ?bool
    {
        return $this->enableSounds;
    }

    public function setEnableSounds(bool $enableSounds): self
    {
        $this->enableSounds = $enableSounds;

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

    public function getLastAction(): ?string
    {
        return $this->lastAction;
    }

    public function setLastAction(?string $lastAction): self
    {
        $this->lastAction = $lastAction;

        return $this;
    }

    public function getLastActivityAt(): ?\DateTimeInterface
    {
        return $this->lastActivityAt;
    }

    public function setLastActivityAt(?\DateTimeInterface $lastActivityAt): self
    {
        $this->lastActivityAt = $lastActivityAt;

        return $this;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function setLastlogin(?\DateTimeInterface $lastlogin): self
    {
        $this->lastlogin = $lastlogin;

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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getMykb(): ?MyKeybroard
    {
        return $this->mykb;
    }

    public function setMykb(?MyKeybroard $mykb): self
    {
        $this->mykb = $mykb;

        return $this;
    }

    public function getSentenceSpaces(): ?int
    {
        return $this->sentenceSpaces;
    }

    public function setSentenceSpaces(int $sentenceSpaces): self
    {
        $this->sentenceSpaces = $sentenceSpaces;

        return $this;
    }


    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        return array($this->role);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @var \Grade
     *
     * @ORM\ManyToOne(targetEntity="Grade")
     */
    private $grade;

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
}
