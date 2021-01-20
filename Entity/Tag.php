<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 *
 * @ORM\Table(name="blog_tags")
 * @ORM\Entity(repositoryClass="bizbink\BlogBundle\Repository\TagRepository")
 */
class Tag
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
     * The slug to be used for permanent URI's
     *
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * The print friendly of the name to display
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Post
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     */
    protected $posts;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Get the unique identifier for this entity
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the print friendly name to be displayed
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName(?string $name): Tag
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the print friendly name to be displayed
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the slug to be used for permanent URI's
     *
     * @param string|null $slug
     * @return Tag
     */
    public function setSlug(?string $slug): Tag
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the slug to be used for permanent URI's
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
