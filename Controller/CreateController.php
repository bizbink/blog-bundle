<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Form\PostType;
use bizbink\BlogBundle\Model\AuthorInterface;
use Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends AbstractController
{

    /**
     * @Route("/create", name="blog_create")
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param EventDispatcherInterface|null $eventDispatcher
     * @return Response
     * @throws Exception
     */
    public function indexAction(Request $request, ManagerRegistry $managerRegistry, EventDispatcherInterface $eventDispatcher)
    {
        $post = new Post();
        $author = $this->getUser();
        if ($author instanceof AuthorInterface) {
            $post->setAuthor($author);
        }

        $form = $this->createForm(PostType::class, $post, [
            'submit_label' => 'Publish',
            'entity_manager'=>$managerRegistry->getManager()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
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
