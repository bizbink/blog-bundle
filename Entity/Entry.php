<?php

namespace bizbink\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entry
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 * @ORM\Table(name="blog_entries")
 * @ORM\Entity(repositoryClass="EntryRepository")
 */
class Entry {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", cascade={"persist"}, inversedBy="entries")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist"}, inversedBy="entries")
     * @ORM\JoinTable(name="blog_entries_tags")
     * */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    public function __construct() {
        $this->tags = new ArrayCollection();
        $this->datetime = new \DateTime('now');
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
     * Set title
     *
     * @param string $title
     *
     * @return Entry
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Entry
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Entry
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime() {
        return $this->datetime;
    }

    /**
     * Set category
     *
     * @param \bizbink\BlogBundle\Entity\Category $category
     *
     * @return Entry
     */
    public function setCategory(\bizbink\BlogBundle\Entity\Category $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \bizbink\BlogBundle\Entity\Category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \bizbink\BlogBundle\Entity\Tag $tag
     *
     * @return Entry
     */
    public function addTag(\bizbink\BlogBundle\Entity\Tag $tag) {
        $tag->addEntry($this);
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \bizbink\BlogBundle\Entity\Tag $tag
     */
    public function removeTag(\bizbink\BlogBundle\Entity\Tag $tag) {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Entry
     */
    public function setAuthor(\AppBundle\Entity\User $author = null) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor() {
        return $this->author;
    }

}
