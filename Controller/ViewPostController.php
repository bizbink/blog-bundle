<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Event\PostEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ViewPostController extends Controller
{

    /**
     * @Route("/{id}-{slug}", name="blog_post", requirements={"id"="\d+"})
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @param $id
     * @param $slug
     * @return Response
     */
    public function indexAction(Request $request, EventDispatcherInterface $eventDispatcher, $id, $slug)
    {

        $post =  $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(["id"=>$id,"slug"=>$slug, 'is_published'=> true]);

        if ($post instanceof Post && $eventDispatcher) {
            $eventDispatcher->dispatch(PostEvent::VIEW,  new PostEvent($post));
        }

        return $this->render('@Blog/blog/index.html.twig', [
            'page' => $id,
            'posts' => [$post],
        ]);
    }
}
