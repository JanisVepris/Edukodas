<?php

namespace Edukodas\Bundle\StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\UserBundle\Entity\OwnedEntityInterface;
use Edukodas\Bundle\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * PointHistory
 *
 * @ORM\Table(name="point_history")
 * @ORM\Entity(repositoryClass="Edukodas\Bundle\StatisticsBundle\Entity\Repository\PointHistoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class PointHistory implements OwnedEntityInterface
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Edukodas\Bundle\UserBundle\Entity\User", inversedBy="pointHistory", fetch="EAGER")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Edukodas\Bundle\UserBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="Edukodas\Bundle\TasksBundle\Entity\Task", fetch="EAGER")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", length=4)
     */
    private $year;

    /**
     * @var int
     *
     * @ORM\Column(name="month", type="integer", length=2)
     */
    private $month;

    /**
     * @var int
     *
     * @ORM\Column(name="day_of_the_month", type="integer", length=2)
     */
    private $dayOfTheMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="day_of_the_week", type="integer", length=1)
     */
    private $dayOfTheWeek;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTime('now');
        }

        $this->setMonth((int) $this->createdAt->format('m'));
        $this->setDayOfTheMonth((int) $this->createdAt->format('d'));
        $this->setDayOfTheWeek((int) $this->createdAt->format('N'));
        $this->setYear((int) $this->createdAt->format('Y'));
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
     * @return User
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param User $teacher
     * @return PointHistory
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return User
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
     * @param Task $task
     *
     * @return PointHistory
     */
    public function setTask(Task $task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return Task
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

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return PointHistory
     */
    public function setYear(int $year): PointHistory
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @param int $month
     *
     * @return PointHistory
     */
    public function setMonth(int $month): PointHistory
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int
     */
    public function getDayOfTheMonth(): int
    {
        return $this->dayOfTheMonth;
    }

    /**
     * @param int $dayOfTheMonth
     *
     * @return PointHistory
     */
    public function setDayOfTheMonth(int $dayOfTheMonth): PointHistory
    {
        $this->dayOfTheMonth = $dayOfTheMonth;
        return $this;
    }

    /**
     * @return int
     */
    public function getDayOfTheWeek(): int
    {
        return $this->dayOfTheWeek;
    }

    /**
     * @param int $dayOfTheWeek
     *
     * @return PointHistory
     */
    public function setDayOfTheWeek(int $dayOfTheWeek): PointHistory
    {
        $this->dayOfTheWeek = $dayOfTheWeek;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return PointHistory
     */
    public function setCreatedAt(\DateTime $createdAt): PointHistory
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->getTeacher();
    }
}
