<?php

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Form\EntryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * EntryController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntryController extends Controller {

    public function indexAction(Request $request, $page) {
        return $this->pageAction($request, $page);
    }

    public function pageAction(Request $request, $page) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntries = $blogEntryRepository->findByPageOrderByIdReversed($page);
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        if (empty($blogEntries)) {
            throw $this->createNotFoundException(
                    'No entries found for page ' . $page
            );
        }
        return $this->render('BlogBundle:Entries:page.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'blog_entries' => $blogEntries,
                    'page' => $page,
        ));
    }

    public function viewAction(Request $request, $id) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntry = $blogEntryRepository->find($id);
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        if (!$blogEntry) {
            throw $this->createNotFoundException(
                    'No entry found for id ' . $id
            );
        }
        return $this->render('BlogBundle:Entries:view.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_entry' => $blogEntry,
                    'blog_tags' => $blogTags,
        ));
    }

    public function createAction(Request $request) {
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        $entry = new \bizbink\BlogBundle\Entity\Entry();
        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entry->setAuthor($this->getUser());
            if (is_null($entry->getContent())) {
                $this->addFlash(
                        'warning', "'Content' field cannot be empty."
                );
            } else {
                $blogTagRespority = $this->getDoctrine()
                        ->getRepository('BlogBundle:Tag');
                foreach ($entry->getTags() as $tag) {
                    $foundTag = $blogTagRespority->findOneByName($tag->getName());
                    if (!empty($foundTag)) {
                        $entry->removeTag($tag);
                        $entry->addTag($foundTag);
                    }
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($entry);
                $em->flush();

                $this->addFlash(
                        'success', 'Entry successfully created!'
                );
            }
        }
        return $this->render('BlogBundle:Entries:editor.html.twig', array(
                    'page_title' => 'entry.new.title',
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'form' => $form->createView(),
                    'submit_button' => 'entry.new.submit',
        ));
    }

    public function editAction(Request $request, $id) {
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        $em = $this->getDoctrine()->getManager();

        $blogEntriesRespority = $em->getRepository('BlogBundle:Entry');

        $entry = $blogEntriesRespority->find($id);

        if (!$entry) {
            throw $this->createNotFoundException(
                    'No entry found for id ' . $id
            );
        }

        $originalTags = new \Doctrine\Common\Collections\ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($entry->getTags() as $tag) {
            $originalTags->add($tag);
        }

        $form = $this->createForm(new EntryType(), $entry);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $entry->setAuthor($this->getUser());

            foreach ($originalTags as $tag) {
                if (false === $entry->getTags()->contains($tag)) {
                    // remove the Entry from the Tag
                    $tag->getEntries()->removeElement($entry);

                    $em->persist($tag);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag);
                }
            }

            // remove duplicates
            $entry->setTags(new \Doctrine\Common\Collections\ArrayCollection(
                    array_unique($entry->getTags()->toArray()
            )));

            foreach ($entry->getTags() as $tag) {
                $foundTag = $blogTagRepository->findOneByName($tag->getName());
                if ($foundTag) {
                    $entry->removeTag($tag);
                    $entry->addTag($foundTag);
                }
            }
            $em->persist($entry);
            $em->flush();

            $this->addFlash(
                    'success', 'Entry successfully saved!'
            );

            return $this->redirectToRoute('bizbink_blog_entry_edit', array('id' => $id));
        }

        return $this->render('BlogBundle:Entries:editor.html.twig', array(
                    'page_title' => 'entry.edit.title',
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'form' => $form->createView(),
                    'submit_button' => 'entry.edit.submit',
        ));
    }

    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $blogEntriesRespority = $em->getRepository('BlogBundle:Entry');

        $entry = $blogEntriesRespority->find($id);
        $em->remove($entry);
        $em->flush();

        $this->addFlash(
                'success', 'Entry successfully deleted!'
        );

        return $this->redirectToRoute('bizbink_blog_entry_list');
    }

}
