<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resume
 *
 * @ORM\Table(name="Resume")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResumeRepository")
 */
class Resume
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
     * @var string
     *
     * @ORM\Column(name="Resume", type="text")
     */
    private $Resume;

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
     * Set Resume
     *
     * @param string $Resume
     *
     * @return Resume
     */
    public function setResume($Resume)
    {
        $this->Resume = $Resume;

        return $this;
    }

    /**
     * Get Resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->Resume;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getResume();
    }
}
