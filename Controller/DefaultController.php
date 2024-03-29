<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Event\PageViewEvent;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="blog")
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function indexAction(Request $request, ManagerRegistry $managerRegistry, EventDispatcherInterface $eventDispatcher = null)
    {
        $page = $request->query->get('page', 1);

        $posts = $managerRegistry
            ->getRepository(Post::class)
            ->findBy(["isPublished" => true], ["created" => "desc"], 3, ($page - 1) * 3);

        if ($eventDispatcher) {
            $eventDispatcher->dispatch(new PageViewEvent($page));
        }

        $hasNext = $managerRegistry
            ->getRepository(Post::class)
            ->findBy(["isPublished" => true], ["created" => "desc"], 3, $page * 3);

        return $this->render('@Blog/blog/index.html.twig', [
            'page' => $page,
            'posts' => $posts,
            'previous_page' => $page != 1 ? $this->generateUrl('blog', ["page" => $page - 1]) : null,
            'next_page' => count($hasNext) > 0 ? $this->generateUrl('blog', ["page" => $page + 1]) : null,
        ]);
    }
}
