<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Event\PageEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="blog")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     */
    public function indexAction(Request $request, EventDispatcherInterface $eventDispatcher = null)
    {
        $page = $request->query->get('page', 1);

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([], ["published" => "desc"], 3, ($page - 1) * 3);


        if ($eventDispatcher) {
            $eventDispatcher->dispatch(PageEvent::VIEW, new PageEvent($page));
        }

        $nextPosts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([], ["published" => "desc"], 3, $page * 3);

        return $this->render('@Blog/blog/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            'page' => $page,
            'posts' => $posts,
            'previous_page' => $page != 1 ? $this->generateUrl('blog', ["page" => $page - 1]) : null,
            'next_page' => count($nextPosts) > 0 ? $this->generateUrl('blog', ["page" => $page + 1]) : null,
        ]);
    }
}
