<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Genre;

/**
 * Serie
 *
 * @ORM\Table(name="Serie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SerieRepository")
 */
class Serie
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Acteur")
     */
    private $acteur;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producteur")
     */
    private $producteur;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Resume")
     */
    private $resume;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image")
     */
    private $image;


    /**
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Genre", cascade={"persist"})
     */
    private $genres;

    /**
     * Serie constructor.
     */
    public function __construct(){
        $this->genres = new ArrayCollection();
    }

    /**
     * @param \AppBundle\Entity\Genre $genre
     */
    public function setGenre(Genre $genre){
        $this->genres[] = $genre;
    }

    /**
     * @param \AppBundle\Entity\Genre $genre
     */
    public function removeGenre(Genre $genre){
        $this->genres->removeElement($genre);
    }

    /**
     * @return ArrayCollection|int
     */
    public function getGenres(){
        return $this->genres;
    }

    /**
     * @return int
     */
    public function getGenre()
    {
        return $this->genres;
    }


    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Serie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return int
     */
    public function getActeur()
    {
        return $this->acteur;
    }

    /**
     * @param int $acteur
     */
    public function setActeur($acteur)
    {
        $this->acteur = $acteur;
    }

    /**
     * @return int
     */
    public function getProducteur()
    {
        return $this->producteur;
    }

    /**
     * @param int $producteur
     */
    public function setProducteur($producteur)
    {
        $this->producteur = $producteur;
    }

    /**
     * @return int
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @param int $resume
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
    }

    /**
     * @return int
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param int $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}

