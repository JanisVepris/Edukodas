<?php

namespace Edukodas\Bundle\TasksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $course;

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
     * @param \Edukodas\Bundle\TasksBundle\Entity\Course $course
     *
     * @return Task
     */
    public function setCourse(\Edukodas\Bundle\TasksBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \Edukodas\Bundle\TasksBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }
}
