<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Form\PostType;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{

    /**
     * @Route("/edit/{id}", name="blog_edit", requirements={"id"="\d+"}, methods={"GET","POST"})
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @param $id
     * @return RedirectResponse|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function index(Request $request, EventDispatcherInterface $eventDispatcher, $id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(["id" => $id]);

        if (!$post instanceof Post) {
            throw $this->createNotFoundException("Could not find post for id " . $id);
        }

        $form = $this->createForm(PostType::class, $post, [
            'submit_label' => 'Save',
            'publish_label' => ($post->isPublished()) ? 'Save & Hide' : 'Save & Publish',
            'entity_manager' => $this->getDoctrine()->getManager()
        ]);

        $originalTags = new ArrayCollection();

        foreach ($post->getTags() as $tag) {
            $originalTags->add($tag);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = "Successfully edited.";
            $type = "success";

            if ($form->get('publish')->isClicked()) {
                $post->setPublished(($post->isPublished()) ? false : true);
                $entityManager->persist($post);
                $entityManager->flush();
                $message = ($post->isPublished()) ? "Successfully published." : "Successfully hidden.";
            } else if ($form->get('delete')->isClicked()) {
                return $this->redirectToRoute("blog_delete", ["id" => $post->getId()]);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute("blog_edit", ["id" => $id]);
        }

        return $this->render('@Blog/blog/editor.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
