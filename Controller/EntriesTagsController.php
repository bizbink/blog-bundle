<?php

namespace bizbink\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * EntriesTagsController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntriesTagsController extends Controller {

    /**
     * @Route("/entries/tags/{tag}/", defaults={"page" = 1}, name="blog_entries_tags")
     */
    public function indexAction(Request $request, $tag, $page) {
        return $this->pageAction($request, $tag, $page);
    }

    /**
     * @Route("/entries/tags/{tag}/page/{page}/", requirements={"page" = "\d+"}, name="blog_entries_tags_page")
     */
    public function pageAction(Request $request, $tag, $page) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntries = $blogEntryRepository->findAllByTagName($tag, $page);
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        if (empty($blogEntries)) {
            throw $this->createNotFoundException();
        }
        return $this->render('BlogBundle:Entries:page.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'blog_entries' => $blogEntries,
                    'page' => $page,
        ));
    }

}
