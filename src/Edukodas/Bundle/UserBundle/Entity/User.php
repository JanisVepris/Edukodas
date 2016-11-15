<?php

namespace Edukodas\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Edukodas\Bundle\TasksBundle\Entity\Course;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="Edukodas\Bundle\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|Course[]
     *
     * @ORM\OneToMany(targetEntity="Edukodas\Bundle\TasksBundle\Entity\Course", mappedBy="user" )
     */
    private $courses;

    /**
     * @var StudentClass
     *
     * @ORM\ManyToOne(targetEntity="StudentClass", inversedBy="students")
     * @ORM\JoinColumn(name="student_class_id", referencedColumnName="id")
     */
    private $studentClass;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->courses = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set student class
     *
     * @param StudentClass $studentClass
     */
    public function setStudentClass(StudentClass $studentClass)
    {
        $this->studentClass = $studentClass;
    }

    /**
     * Get student class
     *
     * @return StudentClass
     */
    public function getStudentClass()
    {
        return $this->studentClass;
    }

    /**
     * Set courses
     *
     * @param $courses
     *
     * @return User
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;

        return $this;
    }

    /**
     * Get courses
     *
     * @return ArrayCollection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Add course
     *
     * @param Course $course
     *
     * @return User
     */
    public function addCourse(Course $course)
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }

        return $this;
    }

    /**
     * Remove course
     *
     * @param Course $course
     *
     * @return User
     */
    public function removeCourse(Course $course)
    {
        if ($this->courses->contains($course)) {
            $this->courses->remove($course);
        }

        return $this;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
