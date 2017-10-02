<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TpolyAcademicRecord
 *
 * @ORM\Table(name="tpoly_academic_record")
 * @ORM\Entity
 */
class TpolyAcademicRecord
{
    /**
     * @var integer
     *
     * @ORM\Column(name="course", type="integer", nullable=false)
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=20, nullable=true)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="credits", type="integer", nullable=true)
     */
    private $credits;

    /**
     * @var integer
     *
     * @ORM\Column(name="student", type="integer", nullable=true)
     */
    private $student;

    /**
     * @var string
     *
     * @ORM\Column(name="indexno", type="string", length=100, nullable=false)
     */
    private $indexno;

    /**
     * @var string
     *
     * @ORM\Column(name="quiz1", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $quiz1;

    /**
     * @var string
     *
     * @ORM\Column(name="quiz2", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $quiz2;

    /**
     * @var string
     *
     * @ORM\Column(name="quiz3", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $quiz3;

    /**
     * @var string
     *
     * @ORM\Column(name="midSem1", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $midsem1;

    /**
     * @var string
     *
     * @ORM\Column(name="exam", type="string", length=5, nullable=true)
     */
    private $exam = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $total = '0.0';

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=2, nullable=true)
     */
    private $grade = 'E';

    /**
     * @var string
     *
     * @ORM\Column(name="gpoint", type="decimal", precision=4, scale=1, nullable=true)
     */
    private $gpoint = '0.0';

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=20, nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="sem", type="string", length=20, nullable=true)
     */
    private $sem;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=11, nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="yrgp", type="string", length=90, nullable=true)
     */
    private $yrgp;

    /**
     * @var string
     *
     * @ORM\Column(name="groups", type="string", length=100, nullable=true)
     */
    private $groups;

    /**
     * @var integer
     *
     * @ORM\Column(name="lecturer", type="integer", nullable=false)
     */
    private $lecturer;

    /**
     * @var string
     *
     * @ORM\Column(name="resit", type="string", length=20, nullable=true)
     */
    private $resit;

    /**
     * @var string
     *
     * @ORM\Column(name="dateRegistered", type="string", length=100, nullable=true)
     */
    private $dateregistered;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdat = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="updates", type="integer", nullable=true)
     */
    private $updates = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * TpolyAcademicRecord constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param int $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * @param int $credits
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;
    }

    /**
     * @return int
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param int $student
     */
    public function setStudent($student)
    {
        $this->student = $student;
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
    public function getQuiz1()
    {
        return $this->quiz1;
    }

    /**
     * @param string $quiz1
     */
    public function setQuiz1($quiz1)
    {
        $this->quiz1 = $quiz1;
    }

    /**
     * @return string
     */
    public function getQuiz2()
    {
        return $this->quiz2;
    }

    /**
     * @param string $quiz2
     */
    public function setQuiz2($quiz2)
    {
        $this->quiz2 = $quiz2;
    }

    /**
     * @return string
     */
    public function getQuiz3()
    {
        return $this->quiz3;
    }

    /**
     * @param string $quiz3
     */
    public function setQuiz3($quiz3)
    {
        $this->quiz3 = $quiz3;
    }

    /**
     * @return string
     */
    public function getMidsem1()
    {
        return $this->midsem1;
    }

    /**
     * @param string $midsem1
     */
    public function setMidsem1($midsem1)
    {
        $this->midsem1 = $midsem1;
    }

    /**
     * @return string
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * @param string $exam
     */
    public function setExam($exam)
    {
        $this->exam = $exam;
    }

    /**
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param string $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param string $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return string
     */
    public function getGpoint()
    {
        return $this->gpoint;
    }

    /**
     * @param string $gpoint
     */
    public function setGpoint($gpoint)
    {
        $this->gpoint = $gpoint;
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
    public function getSem()
    {
        return $this->sem;
    }

    /**
     * @param string $sem
     */
    public function setSem($sem)
    {
        $this->sem = $sem;
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
    public function getYrgp()
    {
        return $this->yrgp;
    }

    /**
     * @param string $yrgp
     */
    public function setYrgp($yrgp)
    {
        $this->yrgp = $yrgp;
    }

    /**
     * @return string
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param string $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return int
     */
    public function getLecturer()
    {
        return $this->lecturer;
    }

    /**
     * @param int $lecturer
     */
    public function setLecturer($lecturer)
    {
        $this->lecturer = $lecturer;
    }

    /**
     * @return string
     */
    public function getResit()
    {
        return $this->resit;
    }

    /**
     * @param string $resit
     */
    public function setResit($resit)
    {
        $this->resit = $resit;
    }

    /**
     * @return string
     */
    public function getDateregistered()
    {
        return $this->dateregistered;
    }

    /**
     * @param string $dateregistered
     */
    public function setDateregistered($dateregistered)
    {
        $this->dateregistered = $dateregistered;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * @param \DateTime $createdat
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;
    }

    /**
     * @return int
     */
    public function getUpdates()
    {
        return $this->updates;
    }

    /**
     * @param int $updates
     */
    public function setUpdates($updates)
    {
        $this->updates = $updates;
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

