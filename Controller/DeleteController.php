<?php

/* 
 * Copyright (C) Matthew Vanderende - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

namespace bizbink\BlogBundle\Controller;

use AppBundle\Entity\User;
use bizbink\BlogBundle\Entity\Post;
use bizbink\BlogBundle\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{

    /**
     * @Route("/delete/{id}", name="blog_delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param PostRepository $postRepository
     * @param EventDispatcherInterface|null $eventDispatcher
     * @param int $id
     * @return RedirectResponse
     */
    public function indexAction(Request $request, PostRepository $postRepository, ?EventDispatcherInterface $eventDispatcher, $id)
    {
        $em = $postRepository->getManager();
        $post = $postRepository
            ->getRepository(Post::class)
            ->find($id);

        if (!$post instanceof Post) {
            throw $this->createNotFoundException("Could not find post for id " . $id);
        }

        $em->remove($post);
        $em->flush();

        $this->addFlash(
            'success',
            'Successfully deleted.'
        );

        return $this->redirectToRoute('blog_manage');
    }
}
