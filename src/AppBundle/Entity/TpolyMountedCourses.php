<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TpolyMountedCourses
 *
 * @ORM\Table(name="tpoly_mounted_courses")
 * @ORM\Entity
 */
class TpolyMountedCourses
{
    /**
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param string $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return string
     */
    public function getCourseCode()
    {
        return $this->courseCode;
    }

    /**
     * @param string $courseCode
     */
    public function setCourseCode($courseCode)
    {
        $this->courseCode = $courseCode;
    }

    /**
     * @return int
     */
    public function getCourseCredit()
    {
        return $this->courseCredit;
    }

    /**
     * @param int $courseCredit
     */
    public function setCourseCredit($courseCredit)
    {
        $this->courseCredit = $courseCredit;
    }

    /**
     * @return int
     */
    public function getCourseSemester()
    {
        return $this->courseSemester;
    }

    /**
     * @param int $courseSemester
     */
    public function setCourseSemester($courseSemester)
    {
        $this->courseSemester = $courseSemester;
    }

    /**
     * @return string
     */
    public function getCourseLevel()
    {
        return $this->courseLevel;
    }

    /**
     * @param string $courseLevel
     */
    public function setCourseLevel($courseLevel)
    {
        $this->courseLevel = $courseLevel;
    }

    /**
     * @return string
     */
    public function getCourseType()
    {
        return $this->courseType;
    }

    /**
     * @param string $courseType
     */
    public function setCourseType($courseType)
    {
        $this->courseType = $courseType;
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
    public function getLecturer()
    {
        return $this->lecturer;
    }

    /**
     * @param string $lecturer
     */
    public function setLecturer($lecturer)
    {
        $this->lecturer = $lecturer;
    }

    /**
     * @return string
     */
    public function getCourseYear()
    {
        return $this->courseYear;
    }

    /**
     * @param string $courseYear
     */
    public function setCourseYear($courseYear)
    {
        $this->courseYear = $courseYear;
    }

    /**
     * @return int
     */
    public function getSync()
    {
        return $this->sync;
    }

    /**
     * @param int $sync
     */
    public function setSync($sync)
    {
        $this->sync = $sync;
    }

    /**
     * @return string
     */
    public function getMountedBy()
    {
        return $this->mountedBy;
    }

    /**
     * @param string $mountedBy
     */
    public function setMountedBy($mountedBy)
    {
        $this->mountedBy = $mountedBy;
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
    /**
     * @var string
     *
     * @ORM\Column(name="COURSE", type="string", length=100, nullable=true)
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(name="COURSE_CODE", type="string", length=100, nullable=false)
     */
    private $courseCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="COURSE_CREDIT", type="integer", nullable=true)
     */
    private $courseCredit;

    /**
     * @var integer
     *
     * @ORM\Column(name="COURSE_SEMESTER", type="integer", nullable=true)
     */
    private $courseSemester;

    /**
     * @var string
     *
     * @ORM\Column(name="COURSE_LEVEL", type="string", length=11, nullable=true)
     */
    private $courseLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="COURSE_TYPE", type="string", length=100, nullable=true)
     */
    private $courseType;

    /**
     * @var string
     *
     * @ORM\Column(name="PROGRAMME", type="string", length=11, nullable=true)
     */
    private $programme;

    /**
     * @var string
     *
     * @ORM\Column(name="LECTURER", type="string", length=100, nullable=true)
     */
    private $lecturer = '54';

    /**
     * @var string
     *
     * @ORM\Column(name="COURSE_YEAR", type="string", length=100, nullable=true)
     */
    private $courseYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="SYNC", type="integer", nullable=false)
     */
    private $sync = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="MOUNTED_BY", type="string", length=11, nullable=false)
     */
    private $mountedBy;

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
     * TpolyMountedCourses constructor.
     */
    public function __construct()
    {
    }


}

