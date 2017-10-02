<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TpolyFeedetails
 *
 * @ORM\Table(name="tpoly_feedetails", uniqueConstraints={@ORM\UniqueConstraint(name="RECEIPTNO", columns={"RECEIPTNO"}), @ORM\UniqueConstraint(name="INDEXNO", columns={"INDEXNO", "RECEIPTNO"})})
 * @ORM\Entity
 */
class TpolyFeedetails
{
    /**
     * @return string
     */
    public function getIndexno()
    {
        return $this->indexno;
    }

    /**
     * @param string $indexno
     */
    public function setIndexno($indexno)
    {
        $this->indexno = $indexno;
    }

    /**
     * @return string
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * @param string $programme
     */
    public function setProgramme($programme)
    {
        $this->programme = $programme;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getNoCopies()
    {
        return $this->noCopies;
    }

    /**
     * @param int $noCopies
     */
    public function setNoCopies($noCopies)
    {
        $this->noCopies = $noCopies;
    }

    /**
     * @return string
     */
    public function getPaymenttype()
    {
        return $this->paymenttype;
    }

    /**
     * @param string $paymenttype
     */
    public function setPaymenttype($paymenttype)
    {
        $this->paymenttype = $paymenttype;
    }

    /**
     * @return string
     */
    public function getPaymentdetails()
    {
        return $this->paymentdetails;
    }

    /**
     * @param string $paymentdetails
     */
    public function setPaymentdetails($paymentdetails)
    {
        $this->paymentdetails = $paymentdetails;
    }

    /**
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @param string $bank
     */
    public function setBank($bank)
    {
        $this->bank = $bank;
    }

    /**
     * @return string
     */
    public function getBankDate()
    {
        return $this->bankDate;
    }

    /**
     * @param string $bankDate
     */
    public function setBankDate($bankDate)
    {
        $this->bankDate = $bankDate;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getReceiptno()
    {
        return $this->receiptno;
    }

    /**
     * @param string $receiptno
     */
    public function setReceiptno($receiptno)
    {
        $this->receiptno = $receiptno;
    }

    /**
     * @return string
     */
    public function getFeeType()
    {
        return $this->feeType;
    }

    /**
     * @param string $feeType
     */
    public function setFeeType($feeType)
    {
        $this->feeType = $feeType;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param string $semester
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;
    }

    /**
     * @return string
     */
    public function getReciepient()
    {
        return $this->reciepient;
    }

    /**
     * @param string $reciepient
     */
    public function setReciepient($reciepient)
    {
        $this->reciepient = $reciepient;
    }

    /**
     * @return \DateTime
     */
    public function getTransdate()
    {
        return $this->transdate;
    }

    /**
     * @param \DateTime $transdate
     */
    public function setTransdate($transdate)
    {
        $this->transdate = $transdate;
    }

    /**
     * @return string
     */
    public function getChecker()
    {
        return $this->checker;
    }

    /**
     * @param string $checker
     */
    public function setChecker($checker)
    {
        $this->checker = $checker;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="INDEXNO", type="string", length=255, nullable=true)
     */
    private $indexno;

    /**
     * @var string
     *
     * @ORM\Column(name="PROGRAMME", type="string", length=100, nullable=true)
     */
    private $programme;

    /**
     * @var string
     *
     * @ORM\Column(name="LEVEL", type="string", length=25, nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="AMOUNT", type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="NO_COPIES", type="integer", nullable=true)
     */
    private $noCopies;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYMENTTYPE", type="string", length=255, nullable=true)
     */
    private $paymenttype;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYMENTDETAILS", type="string", length=255, nullable=true)
     */
    private $paymentdetails;

    /**
     * @var string
     *
     * @ORM\Column(name="BANK", type="string", length=255, nullable=true)
     */
    private $bank;

    /**
     * @var string
     *
     * @ORM\Column(name="BANK_DATE", type="string", length=100, nullable=false)
     */
    private $bankDate;

    /**
     * @var string
     *
     * @ORM\Column(name="TRANSACTION_ID", type="string", length=255, nullable=true)
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="RECEIPTNO", type="string", length=255, nullable=true)
     */
    private $receiptno;

    /**
     * @var string
     *
     * @ORM\Column(name="FEE_TYPE", type="string", length=255, nullable=true)
     */
    private $feeType;

    /**
     * @var string
     *
     * @ORM\Column(name="YEAR", type="string", length=255, nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="SEMESTER", type="string", length=255, nullable=true)
     */
    private $semester;

    /**
     * @var string
     *
     * @ORM\Column(name="RECIEPIENT", type="string", length=222, nullable=true)
     */
    private $reciepient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TRANSDATE", type="datetime", nullable=false)
     */
    private $transdate = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="CHECKER", type="string", length=100, nullable=false)
     */
    private $checker;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * TpolyFeedetails constructor.
     */
    public function __construct()
    {
    }


}

