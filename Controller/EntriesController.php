<?php

namespace bizbink\BlogBundle\Controller;

use bizbink\BlogBundle\Form\EntryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * EntriesController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntriesController extends Controller {

    /**
     * @Route("/entries/", defaults={"page" = 1}, name="blog_entries")
     */
    public function indexAction(Request $request, $page) {
        return $this->pageAction($request, $page);
    }

    /**
     * @Route("/entries/page/{page}/", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="blog_entries_page")
     */
    public function pageAction(Request $request, $page) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogEntries = $blogEntryRepository->findByPageOrderByIdReversed($page);
        $blogCategories = $blogCategoryRepository->findAll();
        if (empty($blogEntries)) {
            throw $this->createNotFoundException(
                    'No entries found for page ' . $page
            );
        }
        return $this->render('BlogBundle:Entries:page.html.twig', array(
                    'page' => $page,
                    'blog_entries' => $blogEntries,
                    'blog_categories' => $blogCategories
        ));
    }

    /**
     * @Route("/entries/{id}/", requirements={"id" = "\d+"}, defaults={"id" = 1}, name="blog_entries_view")
     */
    public function viewAction(Request $request, $id) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogEntry = $blogEntryRepository->find($id);
        if (!$blogEntry) {
            throw $this->createNotFoundException(
                    'No entry found for id ' . $id
            );
        }
        return $this->render('BlogBundle:Entries:view.html.twig', array(
                    'page' => $id,
                    'blog_entry' => $blogEntry
        ));
    }

    /**
     * @Route("/entries/new", name="blog_entries_new")
     */
    public function newAction(Request $request) {
        $form = $this->createForm(new EntryType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entry = $form->getData();
            $entry->setAuthor($this->getUser());
            if (is_null($entry->getContent())) {
                $this->addFlash(
                        'warning', "'Content' field cannot be empty."
                );
            } else {
                $blogTagRespority = $this->getDoctrine()
                        ->getRepository('BlogBundle:Tag');
                foreach ($entry->getTags() as $tag) {
                    $foundTags = $blogTagRespority->findOneByName($tag->getName());
                    if (!empty($foundTags)) {
                        $entry->removeTag($tag);
                        $entry->addTag($foundTag[0]);
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
                    'form' => $form->createView(),
                    'submit_button' => 'entry.new.submit',
                    'page_title' => 'entry.new.title',
        ));
    }

    /**
     * @Route("/entries/edit/{id}", requirements={"id" = "\d+"}, name="blog_entries_edit")
     */
    public function editAction(Request $request, $id) {
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
            $entry->setTitle($form->get('title')->getData());
            $entry->setContent($form->get('content')->getData());
            $entry->setCategory($form->get('category')->getData());
            foreach ($entry->getTags() as $entryTag) {
                $entry->removeTag($entryTag);
            }
            foreach ($form->get('tags')->getData() as $formTag) {
                $entry->addTag($formTag);
            }
            $entry->setDatetime($form->get('datetime')->getData());

            if (is_null($entry->getContent())) {
                $this->addFlash(
                        'warning', "'Content' field cannot be empty."
                );
            } else {
                $blogTagRespority = $this->getDoctrine()
                        ->getRepository('BlogBundle:Tag');
                foreach ($entry->getTags() as $tag) {
                    $foundTag = $blogTagRespority->findOneByName($tag->getName());
                    if ($foundTag) {
                        $entry->removeTag($tag);
                        $entry->addTag($foundTag);
                    }
                }

                $em->flush();

                $this->addFlash(
                        'success', 'Entry successfully saved!'
                );
            }
        }
        return $this->render('BlogBundle:Entries:editor.html.twig', array(
                    'form' => $form->createView(),
                    'submit_button' => 'entry.edit.submit',
                    'page_title' => 'entry.edit.title',
        ));
    }

    /**
     * @Route("/entries/delete/{id}", requirements={"id" = "\d+"}, name="blog_entries_delete")
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $blogEntriesRespority = $em->getRepository('BlogBundle:Entry');

        $entry = $blogEntriesRespority->find($id);
        $em->remove($entry);
        $em->flush();

        $this->addFlash(
                'success', 'Entry successfully deleted!'
        );

        return $this->redirectToRoute('blog_entries_list');
    }

}
