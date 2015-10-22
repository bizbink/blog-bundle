<?php

namespace bizbink\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 * @ORM\Table(name="blog_categories")
 * @ORM\Entity(repositoryClass="CategoryRepository")
 */
class Category {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="category")
     * */
    private $entries;

    /**
     * Constructor
     */
    public function __construct() {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add entry
     *
     * @param \bizbink\BlogBundle\Entity\Entry $entry
     *
     * @return Category
     */
    public function addEntry(\bizbink\BlogBundle\Entity\Entry $entry) {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry
     *
     * @param \bizbink\BlogBundle\Entity\Entry $entry
     */
    public function removeEntry(\bizbink\BlogBundle\Entity\Entry $entry) {
        $this->entries->removeElement($entry);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries() {
        return $this->entries;
    }

}
