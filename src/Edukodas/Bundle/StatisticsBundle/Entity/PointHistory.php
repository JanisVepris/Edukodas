<?php

namespace Edukodas\Bundle\StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PointHistory
 *
 * @ORM\Table(name="point_history")
 * @ORM\Entity()
 */
class PointHistory
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Edukodas\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Edukodas\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @var string
     *
     * @ORM\Column(name="task", type="string", length=255)
     */
    private $task;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;


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
     * @return int
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param int $teacher
     * @return PointHistory
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
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
     * @return PointHistory
     */
    public function setStudent($student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Set task
     *
     * @param string $task
     *
     * @return PointHistory
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return PointHistory
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return PointHistory
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
