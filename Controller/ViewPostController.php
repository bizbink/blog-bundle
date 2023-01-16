<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Event\PostViewEvent;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewPostController extends AbstractController
{

    /**
     * @Route("/{id}-{slug}", name="blog_post", requirements={"id"="\d+"})
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param EventDispatcherInterface|null $eventDispatcher
     * @param $id
     * @param $slug
     * @return Response
     */
    public function indexAction(Request $request, ManagerRegistry $managerRegistry, EventDispatcherInterface $eventDispatcher, $id, $slug)
    {

        $post = $managerRegistry
            ->getRepository(Post::class)
            ->findOneBy(["id" => $id, "slug" => $slug]);

        if ($post instanceof Post && $eventDispatcher) {
            $eventDispatcher->dispatch(new PostViewEvent($post));
        }

        return $this->render('@Blog/blog/index.html.twig', [
            'page' => $id,
            'posts' => [$post],
        ]);
    }
}
