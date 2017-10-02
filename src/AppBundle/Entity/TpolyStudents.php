<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TpolyStudents
 *
 * @ORM\Table(name="tpoly_students")
 * @ORM\Entity
 */
class TpolyStudents
{
    /**
     * @var string
     *
     * @ORM\Column(name="INDEXNO", type="string", length=255, nullable=false)
     */
    private $indexno;

    /**
     * @var string
     *
     * @ORM\Column(name="PROGRAMMECODE", type="string", length=255, nullable=true)
     */
    private $programmecode;

    /**
     * @var string
     *
     * @ORM\Column(name="LEVEL", type="string", length=11, nullable=false)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="YEAR", type="string", length=11, nullable=false)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="STNO", type="string", length=255, nullable=true)
     */
    private $stno;

    /**
     * @var string
     *
     * @ORM\Column(name="SURNAME", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="NAME", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="FIRSTNAME", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="OTHERNAMES", type="string", length=100, nullable=true)
     */
    private $othernames;

    /**
     * @var string
     *
     * @ORM\Column(name="SEX", type="string", length=255, nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="DATEOFBIRTH", type="string", length=255, nullable=true)
     */
    private $dateofbirth;

    /**
     * @var integer
     *
     * @ORM\Column(name="AGE", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="DATE_ADMITTED", type="string", length=255, nullable=true)
     */
    private $dateAdmitted;

    /**
     * @var string
     *
     * @ORM\Column(name="TITLE", type="string", length=20, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="GRADUATING_GROUP", type="string", length=255, nullable=true)
     */
    private $graduatingGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="MARITAL_STATUS", type="string", length=100, nullable=false)
     */
    private $maritalStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="HALL", type="string", length=255, nullable=true)
     */
    private $hall;

    /**
     * @var string
     *
     * @ORM\Column(name="ADDRESS", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="RESIDENTIAL_ADDRESS", type="string", length=100, nullable=true)
     */
    private $residentialAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="EMAIL", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="TELEPHONENO", type="string", length=255, nullable=true)
     */
    private $telephoneno;

    /**
     * @var string
     *
     * @ORM\Column(name="COUNTRY", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="REGION", type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="RELIGION", type="string", length=100, nullable=false)
     */
    private $religion;

    /**
     * @var string
     *
     * @ORM\Column(name="HOMETOWN", type="string", length=100, nullable=false)
     */
    private $hometown;

    /**
     * @var string
     *
     * @ORM\Column(name="GUARDIAN_NAME", type="string", length=100, nullable=false)
     */
    private $guardianName;

    /**
     * @var string
     *
     * @ORM\Column(name="GUARDIAN_ADDRESS", type="string", length=100, nullable=false)
     */
    private $guardianAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="GUARDIAN_PHONE", type="string", length=10, nullable=false)
     */
    private $guardianPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="GUARDIAN_OCCUPATION", type="string", length=100, nullable=false)
     */
    private $guardianOccupation;

    /**
     * @var string
     *
     * @ORM\Column(name="DISABILITY", type="string", length=100, nullable=true)
     */
    private $disability;

    /**
     * @var string
     *
     * @ORM\Column(name="STATUS", type="string", length=100, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="NHIS", type="string", length=100, nullable=false)
     */
    private $nhis;

    /**
     * @var string
     *
     * @ORM\Column(name="STUDENT_TYPE", type="string", length=255, nullable=true)
     */
    private $studentType;

    /**
     * @var string
     *
     * @ORM\Column(name="HOSTEL", type="string", length=100, nullable=true)
     */
    private $hostel;

    /**
     * @var float
     *
     * @ORM\Column(name="CGPA", type="float", precision=10, scale=0, nullable=false)
     */
    private $cgpa = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="CLASS", type="string", length=100, nullable=false)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAGS", type="string", length=100, nullable=false)
     */
    private $flags;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=100, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="TOTAL_CREDIT_DONE", type="integer", nullable=false)
     */
    private $totalCreditDone;

    /**
     * @var integer
     *
     * @ORM\Column(name="CREDIT_LEFT_COMPLETE", type="integer", nullable=false)
     */
    private $creditLeftComplete;

    /**
     * @var integer
     *
     * @ORM\Column(name="ALLOW_REGISTER", type="integer", nullable=false)
     */
    private $allowRegister = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="ALLOW_RESULT", type="integer", nullable=false)
     */
    private $allowResult = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="REGISTERED", type="integer", nullable=false)
     */
    private $registered;

    /**
     * @var integer
     *
     * @ORM\Column(name="QUALITY_ASSURANCE", type="integer", nullable=false)
     */
    private $qualityAssurance;

    /**
     * @var integer
     *
     * @ORM\Column(name="LIAISON", type="integer", nullable=false)
     */
    private $liaison;

    /**
     * @var integer
     *
     * @ORM\Column(name="HAS_PASSWORD", type="integer", nullable=false)
     */
    private $hasPassword = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="SYSUPDATE", type="integer", nullable=false)
     */
    private $sysupdate;

    /**
     * @var float
     *
     * @ORM\Column(name="BILLS", type="float", precision=10, scale=0, nullable=true)
     */
    private $bills = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="BILL_OWING", type="float", precision=10, scale=0, nullable=true)
     */
    private $billOwing = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SMS_SENT", type="integer", nullable=false)
     */
    private $smsSent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="INPUTEDDATE", type="datetime", nullable=false)
     */
    private $inputeddate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * TpolyStudents constructor.
     */
    public function __construct()
    {
    }

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
    public function getProgrammecode()
    {
        return $this->programmecode;
    }

    /**
     * @param string $programmecode
     */
    public function setProgrammecode($programmecode)
    {
        $this->programmecode = $programmecode;
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
    public function getStno()
    {
        return $this->stno;
    }

    /**
     * @param string $stno
     */
    public function setStno($stno)
    {
        $this->stno = $stno;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getOthernames()
    {
        return $this->othernames;
    }

    /**
     * @param string $othernames
     */
    public function setOthernames($othernames)
    {
        $this->othernames = $othernames;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

    /**
     * @param string $dateofbirth
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function getDateAdmitted()
    {
        return $this->dateAdmitted;
    }

    /**
     * @param string $dateAdmitted
     */
    public function setDateAdmitted($dateAdmitted)
    {
        $this->dateAdmitted = $dateAdmitted;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getGraduatingGroup()
    {
        return $this->graduatingGroup;
    }

    /**
     * @param string $graduatingGroup
     */
    public function setGraduatingGroup($graduatingGroup)
    {
        $this->graduatingGroup = $graduatingGroup;
    }

    /**
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param string $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * @return string
     */
    public function getHall()
    {
        return $this->hall;
    }

    /**
     * @param string $hall
     */
    public function setHall($hall)
    {
        $this->hall = $hall;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getResidentialAddress()
    {
        return $this->residentialAddress;
    }

    /**
     * @param string $residentialAddress
     */
    public function setResidentialAddress($residentialAddress)
    {
        $this->residentialAddress = $residentialAddress;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getTelephoneno()
    {
        return $this->telephoneno;
    }

    /**
     * @param string $telephoneno
     */
    public function setTelephoneno($telephoneno)
    {
        $this->telephoneno = $telephoneno;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param string $religion
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
    }

    /**
     * @return string
     */
    public function getHometown()
    {
        return $this->hometown;
    }

    /**
     * @param string $hometown
     */
    public function setHometown($hometown)
    {
        $this->hometown = $hometown;
    }

    /**
     * @return string
     */
    public function getGuardianName()
    {
        return $this->guardianName;
    }

    /**
     * @param string $guardianName
     */
    public function setGuardianName($guardianName)
    {
        $this->guardianName = $guardianName;
    }

    /**
     * @return string
     */
    public function getGuardianAddress()
    {
        return $this->guardianAddress;
    }

    /**
     * @param string $guardianAddress
     */
    public function setGuardianAddress($guardianAddress)
    {
        $this->guardianAddress = $guardianAddress;
    }

    /**
     * @return string
     */
    public function getGuardianPhone()
    {
        return $this->guardianPhone;
    }

    /**
     * @param string $guardianPhone
     */
    public function setGuardianPhone($guardianPhone)
    {
        $this->guardianPhone = $guardianPhone;
    }

    /**
     * @return string
     */
    public function getGuardianOccupation()
    {
        return $this->guardianOccupation;
    }

    /**
     * @param string $guardianOccupation
     */
    public function setGuardianOccupation($guardianOccupation)
    {
        $this->guardianOccupation = $guardianOccupation;
    }

    /**
     * @return string
     */
    public function getDisability()
    {
        return $this->disability;
    }

    /**
     * @param string $disability
     */
    public function setDisability($disability)
    {
        $this->disability = $disability;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getNhis()
    {
        return $this->nhis;
    }

    /**
     * @param string $nhis
     */
    public function setNhis($nhis)
    {
        $this->nhis = $nhis;
    }

    /**
     * @return string
     */
    public function getStudentType()
    {
        return $this->studentType;
    }

    /**
     * @param string $studentType
     */
    public function setStudentType($studentType)
    {
        $this->studentType = $studentType;
    }

    /**
     * @return string
     */
    public function getHostel()
    {
        return $this->hostel;
    }

    /**
     * @param string $hostel
     */
    public function setHostel($hostel)
    {
        $this->hostel = $hostel;
    }

    /**
     * @return float
     */
    public function getCgpa()
    {
        return $this->cgpa;
    }

    /**
     * @param float $cgpa
     */
    public function setCgpa($cgpa)
    {
        $this->cgpa = $cgpa;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param string $flags
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getTotalCreditDone()
    {
        return $this->totalCreditDone;
    }

    /**
     * @param int $totalCreditDone
     */
    public function setTotalCreditDone($totalCreditDone)
    {
        $this->totalCreditDone = $totalCreditDone;
    }

    /**
     * @return int
     */
    public function getCreditLeftComplete()
    {
        return $this->creditLeftComplete;
    }

    /**
     * @param int $creditLeftComplete
     */
    public function setCreditLeftComplete($creditLeftComplete)
    {
        $this->creditLeftComplete = $creditLeftComplete;
    }

    /**
     * @return int
     */
    public function getAllowRegister()
    {
        return $this->allowRegister;
    }

    /**
     * @param int $allowRegister
     */
    public function setAllowRegister($allowRegister)
    {
        $this->allowRegister = $allowRegister;
    }

    /**
     * @return int
     */
    public function getAllowResult()
    {
        return $this->allowResult;
    }

    /**
     * @param int $allowResult
     */
    public function setAllowResult($allowResult)
    {
        $this->allowResult = $allowResult;
    }

    /**
     * @return int
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param int $registered
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    }

    /**
     * @return int
     */
    public function getQualityAssurance()
    {
        return $this->qualityAssurance;
    }

    /**
     * @param int $qualityAssurance
     */
    public function setQualityAssurance($qualityAssurance)
    {
        $this->qualityAssurance = $qualityAssurance;
    }

    /**
     * @return int
     */
    public function getLiaison()
    {
        return $this->liaison;
    }

    /**
     * @param int $liaison
     */
    public function setLiaison($liaison)
    {
        $this->liaison = $liaison;
    }

    /**
     * @return int
     */
    public function getHasPassword()
    {
        return $this->hasPassword;
    }

    /**
     * @param int $hasPassword
     */
    public function setHasPassword($hasPassword)
    {
        $this->hasPassword = $hasPassword;
    }

    /**
     * @return int
     */
    public function getSysupdate()
    {
        return $this->sysupdate;
    }

    /**
     * @param int $sysupdate
     */
    public function setSysupdate($sysupdate)
    {
        $this->sysupdate = $sysupdate;
    }

    /**
     * @return float
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @param float $bills
     */
    public function setBills($bills)
    {
        $this->bills = $bills;
    }

    /**
     * @return float
     */
    public function getBillOwing()
    {
        return $this->billOwing;
    }

    /**
     * @param float $billOwing
     */
    public function setBillOwing($billOwing)
    {
        $this->billOwing = $billOwing;
    }

    /**
     * @return int
     */
    public function getSmsSent()
    {
        return $this->smsSent;
    }

    /**
     * @param int $smsSent
     */
    public function setSmsSent($smsSent)
    {
        $this->smsSent = $smsSent;
    }

    /**
     * @return \DateTime
     */
    public function getInputeddate()
    {
        return $this->inputeddate;
    }

    /**
     * @param \DateTime $inputeddate
     */
    public function setInputeddate($inputeddate)
    {
        $this->inputeddate = $inputeddate;
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


}

