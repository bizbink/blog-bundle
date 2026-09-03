<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Event\PostViewEvent;
use bizbink\BlogBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewPostController extends AbstractController
{

    /**
     * @param Request $request
     * @param PostRepository $postRepository
     * @param EventDispatcherInterface|null $eventDispatcher
     * @param $id
     * @param $slug
     * @return Response
     */
    #[Route('/{id}-{slug}', name: 'blog_post', requirements: ['id' => '\d+'])]
    public function indexAction(Request $request, PostRepository $postRepository, EventDispatcherInterface $eventDispatcher, $id, $slug)
    {

        $post = $postRepository
            ->findOneBy(["id" => $id, "slug" => $slug]);

        // A missing post must be a real 404. Rendering with a null post used
        // to behave differently per environment: dev/test (Twig
        // strict_variables on) threw a 500 at the first attribute access,
        // while prod (strict_variables off) silently served the page shell
        // with a 200 and then handed null to Parsedown, triggering a PHP
        // deprecation on every request for a nonexistent id.
        if (!$post instanceof Post) {
            throw $this->createNotFoundException(sprintf('No post with id %d and slug "%s".', $id, $slug));
        }

        $eventDispatcher->dispatch(new PostViewEvent($post));

        return $this->render('@Blog/blog/index.html.twig', [
            'page' => $id,
            'posts' => [$post],
        ]);
    }
}
