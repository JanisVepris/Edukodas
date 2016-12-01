<?php

namespace Edukodas\Bundle\TasksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Edukodas\Bundle\UserBundle\Entity\User;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="Edukodas\Bundle\TasksBundle\Repository\CourseRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Course
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
     * @ORM\ManyToOne(targetEntity="Edukodas\Bundle\UserBundle\Entity\User", inversedBy="courses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var ArrayCollection|Task[]
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="course")
     */
    private $tasks;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Course
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tasks
     *
     * @param $tasks
     *
     * @return Course
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Get tasks
     *
     * @return ArrayCollection|Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add task
     *
     * @param Task $task
     *
     * @return Course
     */
    public function addTask(Task $task)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }

        return $this;
    }

    /**
     * Remove task
     *
     * @param Task $task
     *
     * @return Course
     */
    public function removeTask(Task $task)
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->remove($task);
        }

        return $this;
    }

    /**
     * Get course name prepended with teacher name
     *
     * @return string
     */
    public function getExtendedName()
    {
        return $this->getName() . ' - ' . $this->getUser()->getFullName();
    }
}
