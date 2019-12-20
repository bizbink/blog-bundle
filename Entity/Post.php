<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Entity;

use bizbink\BlogBundle\Model\AuthorInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints\Date;


/**
 * Post
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 *
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="bizbink\BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * The unique identifier for this entity
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The author of this entity
     *
     * @var AuthorInterface
     *
     * @ORM\ManyToOne(targetEntity="\bizbink\BlogBundle\Model\AuthorInterface")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * The print friendly title to be displayed
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * The slug to be used for permanent URI's
     *
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * The raw content of this entity
     *
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    /**
     * Determines weather the post is publicly visible
     *
     * @var bool
     *
     * @ORM\Column(name="is_published", type="boolean", )
     */
    private $isPublished;
    /**
     * The date and time this entity was published
     *
     * @var DateTime
     *
     * @ORM\Column(name="published", type="datetime")
     */
    private $published;
    /**
     * The date and time this entity was edited
     *
     * @var DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;
    private $tagsText;
    /**
     * The collection of Tag entities mapped to this entity
     *
     * @var ArrayCollection|Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist"})
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    private $tags;
    /**
     * The collection of Category entities mapped to this entity
     *
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    /**
     * The number of views
     *
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;

    /**
     * Constructor
     *
     * Required parameters are 'author', 'title', 'slug', and 'content'
     *
     * The parameters 'published' and 'updated' default to the current date and time
     *
     * @param $author
     * @param string $title
     * @param string $slug
     * @param string $content
     * @param Category|null $category
     * @param ArrayCollection|null $tags
     * @param bool|null $isPublished
     * @param DateTime|null $published
     * @param DateTime|null $updated
     * @param int|null $views
     * @throws Exception
     */
    public function __construct($author = null, string $title = null, string $slug = null, string $content = null, Category $category = null, ArrayCollection $tags = null, bool $isPublished = null, DateTime $published = null, DateTime $updated = null, int $views = null)
    {
        if (!is_null($author)) {
            $this->author = $author;
        }
        if (!is_null($title)) {
            $this->title = $title;
        }
        if (!is_null($slug)) {
            $this->slug = $slug;
        }
        if (!is_null($content)) {
            $this->content = $content;
        }
        if (!is_null($category)) {
            $this->category = $category;
        }
        if (!is_null($tags)) {
            $this->tags = $tags;
        } else {
            $this->tags = new ArrayCollection();
        }
        if (!is_null($published)) {
            $this->published = $published;
        } else {
            $this->published = new DateTime('now');
        }
        if (!is_null($this->isPublished)) {
            $this->isPublished = $isPublished;
        } else {
            $this->isPublished = false;
        }
        if (!is_null($updated)) {
            $this->updated = $updated;
        } else {
            $this->updated = new DateTime('now');
        }
        if (!is_null($views)) {
            $this->views = $views;
        } else {
            $this->views = 0;
        }
    }

    /**
     * Get publicly visibility
     * @return boolean|null
     */
    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * Set publicly visibility
     *
     * @param boolean|null $isPublished
     */
    public function setIsPublished(bool $isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return string
     */
    public function getTagsText(): ?string
    {
        return $this->tagsText;
    }

    /**
     * @param string|null $tagsText
     * @return Post
     */
    public function setTagsText(?string $tagsText): Post
    {
        $this->tagsText = $tagsText;
        return $this;
    }

    /**
     * @param string $tagsText
     * @return Post
     */
    public function appendTagsText(string $tagsText): Post
    {
        $this->tagsText .= $tagsText;
        return $this;
    }

    /**
     * Get the unique identifier for this entity
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the author of this entity
     *
     * @return AuthorInterface|null
     */
    public function getAuthor(): ?AuthorInterface
    {
        return $this->author;
    }

    /**
     * Set the author of this entity
     *
     * @param AuthorInterface $author
     *
     * @return Post
     */
    public function setAuthor(AuthorInterface $author): Post
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the print friendly title to be displayed
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the print friendly title to be displayed
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle(?string $title): Post
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the slug for permanent URI's
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the slug for permanent URI's
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug(?string $slug): Post
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the raw content of this entity
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the raw content of this entity
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent(?string $content): Post
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the date and time this entity was published
     *
     * @return DateTime|null
     */
    public function getPublished(): ?DateTime
    {
        return $this->published;
    }

    /**
     * Set the date and time this entity was published
     *
     * @param DateTime $published
     *
     * @return Post
     */
    public function setPublished(?DateTime $published): Post
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get the date and time this entity was edited
     *
     * @return DateTime|null
     */
    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    /**
     * Set updated.
     *
     * @param DateTime $updated
     *
     * @return Post
     */
    public function setUpdated(?DateTime $updated): Post
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add a Tag entity
     *
     * @param Tag $tag
     *
     * @return Post
     */
    public function addTag(Tag $tag): Post
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Get the ArrayCollection of Tag entities
     *
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Get the Category entity
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set the Category entity
     *
     * @param Category $category
     *
     * @return Post
     */
    public function setCategory(?Category $category): Post
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTag(Tag $tag): bool
    {
        return $this->tags->removeElement($tag);
    }

    /**
     * Get views.
     *
     * @return int|null
     */
    public function getViews(): ?int
    {
        return $this->views;
    }

    /**
     * Set views.
     *
     * @param int $views
     *
     * @return Post
     */
    public function setViews(?int $views): Post
    {
        $this->views = $views;

        return $this;
    }
}
