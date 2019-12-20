<?php

/*
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace Tests\BlogBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use BlogBundle\Entity\Category;
use BlogBundle\Repository\CategoryRepository;

/**
 * Description of PostTest
 *
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class CategoryTest extends TestCase {
    public function testPostInteraction() {
        
        $expectedCategoryValues = ["name" => "Sample Category", "slug"=>"category-sample"];
        $category = new Category($expectedCategoryValues["name"], $expectedCategoryValues["slug"]);
        
        $categoryRepository = $this->createMock(ObjectRepository::class);
        $categoryRepository->expects($this->any())
            ->method('find')
            ->willReturn($expectedCategoryValues);
        
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($category);
        
        $this->assertEquals($expectedCategoryValues["name"], $category->getName());
        $this->assertEquals($expectedCategoryValues["slug"], $category->getSlug());
    }
}