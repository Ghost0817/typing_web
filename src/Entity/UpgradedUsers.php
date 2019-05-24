<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UpgradedUsers
 *
 * @ORM\Table(name="upgraded_users", indexes={@ORM\Index(name="student_id", columns={"student_id"}), @ORM\Index(name="upgrade_id", columns={"upgrade_id", "student_id"}), @ORM\Index(name="IDX_82627C798729BBE", columns={"upgrade_id"})})
 * @ORM\Entity
 */
class UpgradedUsers
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
     * @ORM\Column(name="tran_date", type="date", nullable=false)
     */
    private $tranDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="exp_date", type="date", nullable=false)
     */
    private $expDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=25, nullable=false)
     */
    private $ipAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_paid", type="boolean", nullable=false)
     */
    private $isPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_number", type="string", length=15, nullable=false, options={"comment"="invoice number is here."})
     */
    private $invoiceNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_send", type="boolean", nullable=false, options={"comment"="Мэйл илгээсэнг тэмдэглэнэ."})
     */
    private $isSend;

    /**
     * @var \Mature
     *
     * @ORM\ManyToOne(targetEntity="Mature")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="upgrade_id", referencedColumnName="id")
     * })
     */
    private $upgrade;

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

    public function getTranDate(): ?\DateTimeInterface
    {
        return $this->tranDate;
    }

    public function setTranDate(\DateTimeInterface $tranDate): self
    {
        $this->tranDate = $tranDate;

        return $this;
    }

    public function getExpDate(): ?\DateTimeInterface
    {
        return $this->expDate;
    }

    public function setExpDate(\DateTimeInterface $expDate): self
    {
        $this->expDate = $expDate;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getIsSend(): ?bool
    {
        return $this->isSend;
    }

    public function setIsSend(bool $isSend): self
    {
        $this->isSend = $isSend;

        return $this;
    }

    public function getUpgrade(): ?Mature
    {
        return $this->upgrade;
    }

    public function setUpgrade(?Mature $upgrade): self
    {
        $this->upgrade = $upgrade;

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
