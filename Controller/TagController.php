<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{

    /**
     * @Route("/tag/{slug}", name="blog_post_tag")
     * @param Request $request
     * @param PostRepository $managerRegistry
     * @param $slug
     * @return RedirectResponse|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function indexAction(Request $request, PostRepository $postRepository, string $slug)
    {
        $page = $request->query->get('page', 1);

        $posts = $postRepository
            ->findByTagSlug($slug, 3, ($page - 1) * 3);

        $hasNext = $postRepository
            ->getRepository(Post::class) 
            ->findByTagSlug($slug, 3, $page * 3);

        return $this->render('@Blog/blog/index.html.twig', [
            'page' => $page,
            'posts' => $posts,
            'previous_page' => $page != 1 ? $this->generateUrl('blog', ["page" => $page - 1]) : null,
            'next_page' => count($hasNext) > 0 ? $this->generateUrl('blog', ["page" => $page + 1]) : null,
        ]);
    }
}
