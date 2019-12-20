<?php

/*
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace Tests\BlogBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use BlogBundle\Entity\Post;
use AppBundle\Entity\User;
use BlogBundle\Entity\Category;
use BlogBundle\Entity\Tag;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of PostTest
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class PostTest extends TestCase {
    public function testPostInteraction() {
        $expectedAuthorValues = new User("bizbink", "password", true, new DateTime());
        
        $expectedCategoryValues = new Category("Sample Category", "category-sample");
        
        $expectedTagValues = new ArrayCollection([
            new Tag("Sample Tag", "tag-sample"),
            new Tag("Sample Tag", "tag-sample"),
        ]);
        
        $expectedPostValues = [
            "author" => $expectedAuthorValues,
            "title" => "Sample Title",
            "slug" => "sample-slug",
            "content" => "Sample Content.",
            "category" => $expectedCategoryValues,
            "tags" => $expectedTagValues,
            "published" => new DateTime(),
            "edited" => new DateTime()
        ];
        $post = new Post($expectedPostValues["author"], $expectedPostValues["title"], $expectedPostValues["slug"],
                $expectedPostValues["content"], $expectedPostValues["category"], $expectedPostValues["tags"],
                $expectedPostValues["published"], $expectedPostValues["edited"]);
        
        $postRepository = $this->createMock(ObjectRepository::class);
        $postRepository->expects($this->any())
            ->method('find')
            ->willReturn($post);
        
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($postRepository);

        $this->assertEquals($expectedPostValues["author"]->getUsername(), $post->getAuthor()->getUsername());
        $this->assertEquals($expectedPostValues["title"], $post->getTitle());
        $this->assertEquals($expectedPostValues["slug"], $post->getSlug());
        $this->assertEquals($expectedPostValues["content"], $post->getContent());
        $this->assertEquals($expectedPostValues["category"], $post->getCategory());
        $this->assertEquals($expectedPostValues["tags"], $post->getTags());
        $this->assertEquals($expectedPostValues["published"], $post->getPublished());
        $this->assertEquals($expectedPostValues["edited"], $post->getUpdated());
    }
}