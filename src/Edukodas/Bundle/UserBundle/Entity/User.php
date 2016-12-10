<?php

namespace Edukodas\Bundle\UserBundle\Entity;

use Edukodas\Bundle\StatisticsBundle\Entity\PointHistory;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Edukodas\Bundle\TasksBundle\Entity\Course;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="Edukodas\Bundle\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User extends BaseUser
{
    use SoftDeleteableEntity;

    const STUDENT_ROLE = 'ROLE_STUDENT';

    const TEACHER_ROLE = 'ROLE_TEACHER';

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
     * @var StudentTeam
     *
     * @ORM\ManyToOne(targetEntity="StudentTeam", inversedBy="students")
     * @ORM\JoinColumn(name="student_team_id", referencedColumnName="id")
     */
    private $studentTeam;

    /**
     * @var StudentGeneration
     *
     * @ORM\ManyToOne(targetEntity="StudentGeneration", inversedBy="students")
     * @ORM\JoinColumn(name="student_generation_id", referencedColumnName="id")
     */
    private $studentGeneration;

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
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=256)
     */
    private $fullName;

    /**
     * @var ArrayCollection|PointHistory
     *
     * @ORM\OneToMany(targetEntity="Edukodas\Bundle\StatisticsBundle\Entity\PointHistory", mappedBy="teacher")
     */
    public $pointHistory;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="picture", nullable=true)
     * @Assert\Image(
     *     maxSize="2M",
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage = "profile.edit.picture.mime_type_error",
     *     maxSizeMessage="profile.edit.picture.max_size_error",
     *     uploadErrorMessage="profile.edit.picture.error."
     * )
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_path", type="text", nullable=true)
     */
    private $picturePath;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->pointHistory = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateFullName()
    {
        $fullName = $this->getFirstName() . ' ' . $this->getLastName();
        $this->setFullName($fullName);
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
     *
     * @return User
     */
    public function setStudentClass(StudentClass $studentClass)
    {
        $this->studentClass = $studentClass;

        return $this;
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
     * Set student team
     *
     * @param StudentTeam $team
     * @return User
     */
    public function setStudentTeam(StudentTeam $team)
    {
        $this->studentTeam = $team;

        return $this;
    }

    /**
     * Get student team
     *
     * @return StudentTeam
     */
    public function getStudentTeam()
    {
        return $this->studentTeam;
    }

    /**
     * Set student generation
     *
     * @param StudentGeneration $generation
     *
     * @return User
     */
    public function setStudentGeneration(StudentGeneration $generation)
    {
        $this->studentGeneration = $generation;

        return $this;
    }

    /**
     * Get student generation
     *
     * @return StudentGeneration
     */
    public function getStudentGeneration()
    {
        return $this->studentGeneration;
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

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     *
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @param mixed $picturePath
     *
     * @return User
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }


    /**
     * @return bool
     */
    public function hasPicture()
    {
        if ($this->getPicturePath()) {
            return true;
        }

        return false;
    }
}
