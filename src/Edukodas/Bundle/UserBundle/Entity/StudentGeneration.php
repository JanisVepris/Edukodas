<?php

namespace Edukodas\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Generation
 *
 * @ORM\Table(name="student_generation")
 * @ORM\Entity(repositoryClass="Edukodas\Bundle\UserBundle\Repository\GenerationRepository")
 */
class StudentGeneration
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date", unique=true)
     */
    private $year;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="studentGeneration")
     */
    private $students;

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
     * Set year
     *
     * @param \DateTime $year
     *
     * @return StudentGeneration
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set students
     *
     * @param ArrayCollection $students
     *
     * @return StudentGeneration
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
}

