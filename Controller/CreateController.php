<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use AppBundle\Entity\User;
use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Entity\Tag;
use bizbink\BlogBundle\Form\PostType;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateController extends Controller
{

    /**
     * @Route("/create", name="blog_create")
     * @param Request $request
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     * @throws Exception
     */
    public function indexAction(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $post = new Post();
        $author = $this->getUser();
        if ($author instanceof User) {
            $post->setAuthor($author);
        }

        $form = $this->createForm(PostType::class, $post, [
            'submit_label' => 'Publish',
            'entity_manager'=>$this->getDoctrine()->getManager()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = "Successfully published.";
            $type = "success";

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute("blog_edit", ["id" => $post->getId()]);
        }

        return $this->render('@Blog/blog/editor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
