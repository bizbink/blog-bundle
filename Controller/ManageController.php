<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use AppBundle\Entity\User;
use bizbink\BlogBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageController extends AbstractController
{

    /**
     * @Route("/manage", name="blog_manage")
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     * @throws Exception
     */
    public function indexAction(Request $request, ManagerRegistry $managerRegistry, ?EventDispatcherInterface $eventDispatcher)
    {
        $page = $request->query->get('page', 1);

        $posts = $managerRegistry
            ->getRepository(Post::class)
            ->findBy([], ["created" => "desc"], 10, ($page - 1) * 10);

        $nextPosts = $managerRegistry
            ->getRepository(Post::class)
            ->findBy([], ["created" => "desc"], 10, $page * 10);

        return $this->render('@Blog/blog/manage.html.twig', [
            'page' => $page,
            'posts' => $posts,
            'previous_page' => $page != 1 ? $this->generateUrl('blog_manage', ["page" => $page - 1]) : null,
            'next_page' => count($nextPosts) > 0 ? $this->generateUrl('blog_manage', ["page" => $page + 1]) : null,
        ]);
    }
}
