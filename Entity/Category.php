<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 *
 * @ORM\Table(name="blog_categories")
 * @ORM\Entity(repositoryClass="bizbink\BlogBundle\Repository\CategoryRepository")
 */
class Category
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
     * The print friendly name to display
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * The slug to be used for permanent URI's
     * 
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $slug
     */
    public function __construct(string $name = null, string $slug = null)
    {
        if (!is_null($name)) {
            $this->name = $name;
        }
        if (!is_null($slug)) {
            $this->slug = $slug;
        }
    }

    /**
     * Get the unique identifier for this entity
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set the print friendly name to be displayed
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name) : Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the print friendly name to be displayed
     *
     * @return string|null
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Set the slug for permanent URI's
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug(string $slug) : Category
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the slug for permanent URI's
     *
     * @return string|null
     */
    public function getSlug() : ?string
    {
        return $this->slug;
    }
}
