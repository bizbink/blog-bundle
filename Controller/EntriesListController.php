<?php

namespace bizbink\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * EntriesListController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntriesListController extends Controller {

    /**
     * @Route("/entries/list", defaults={"page" = 1}, name="blog_entries_list")
     */
    public function indexAction(Request $request, $page) {
        return $this->pageAction($request, $page);
    }

    /**
     * @Route("/entries/list/page/{page}/", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="blog_entries_list_page")
     */
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
        return $this->render('BlogBundle:Entries:list.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'blog_entries' => $blogEntries,
                    'page' => $page,
        ));
    }

}
