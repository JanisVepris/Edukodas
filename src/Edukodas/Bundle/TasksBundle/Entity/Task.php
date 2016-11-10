<?php

namespace Edukodas\Bundle\TasksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edukodas\Bundle\TasksBundle\Entity\Course;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Edukodas\Bundle\TasksBundle\Repository\TaskRepository")
 */
class Task
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
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="tasks")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(name="task_name", type="string", length=255)
     */
    private $taskName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="task_descr", type="string", length=255)
     */
    private $taskDescr;

    /**
     * @var int
     *
     * @ORM\Column(name="task_points", type="integer")
     */
    private $taskPoints;


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
     * Set taskDescr
     *
     * @param string $taskDescr
     *
     * @return Task
     */
    public function setTaskDescr($taskDescr)
    {
        $this->taskDescr = $taskDescr;

        return $this;
    }

    /**
     * Get taskDescr
     *
     * @return string
     */
    public function getTaskDescr()
    {
        return $this->taskDescr;
    }

    /**
     * Set taskPoints
     *
     * @param integer $taskPoints
     *
     * @return Task
     */
    public function setTaskPoints($taskPoints)
    {
        $this->taskPoints = $taskPoints;

        return $this;
    }

    /**
     * Get taskPoints
     *
     * @return int
     */
    public function getTaskPoints()
    {
        return $this->taskPoints;
    }

    /**
     * Set course
     *
     * @param Course $course
     *
     * @return Task
     */
    public function setCourse(Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set taskName
     *
     * @param string $taskName
     *
     * @return Task
     */
    public function setTaskName($taskName)
    {
        $this->taskName = $taskName;

        return $this;
    }

    /**
     * Get taskName
     *
     * @return string
     */
    public function getTaskName()
    {
        return $this->taskName;
    }
}
