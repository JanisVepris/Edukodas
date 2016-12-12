<?php

namespace Edukodas\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Edukodas\Bundle\UserBundle\Entity\User;

/**
 * StudentTeam
 *
 * @ORM\Table(name="student_team")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class StudentTeam
{
    use SoftDeleteableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128)
     */
    private $title;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="studentTeam")
     */
    private $students;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=20)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="graph_color", type="string", length=6)
     */
    private $graphColor;

    /**
     * StudentTeam constructor.
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return StudentTeam
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set students
     *
     * @param ArrayCollection $students
     *
     * @return StudentTeam
     */
    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }

    /**
     * Get students
     *
     * @return ArrayCollection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add student
     *
     * @param User $student
     *
     * @return $this
     */
    public function addStudent(User $student)
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
        }

        return $this;
    }

    /**
     * Remove student
     *
     * @param User $student
     *
     * @return $this
     */
    public function removeStudent(User $student)
    {
        if ($this->students->contains($student)) {
            $this->students->remove($student);
        }

        return $this;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return StudentTeam
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getGraphColor(): string
    {
        return $this->graphColor;
    }

    /**
     * @param string $graphColor
     *
     * @return StudentTeam
     */
    public function setGraphColor(string $graphColor): StudentTeam
    {
        $this->graphColor = $graphColor;
        return $this;
    }
}
