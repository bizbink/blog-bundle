<?php

namespace bizbink\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * EntriesCategoriesController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class EntriesCategoriesController extends Controller {

    /**
     * @Route("/entries/categories/{category}/", defaults={"page" = 1}, name="blog_entries_categories")
     */
    public function indexAction(Request $request, $category, $page) {
        return $this->pageAction($request, $category, $page);
    }

    /**
     * @Route("/entries/categories/{category}/page/{page}/", requirements={"page" = "\d+"}, name="blog_entries_categories_page")
     */
    public function pageAction(Request $request, $category, $page) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogEntries = $blogEntryRepository->findAllByCategoryName($category, $page);
        if (empty($blogEntries)) {
            throw $this->createNotFoundException();
        }
        return $this->render('BlogBundle:Entries:page.html.twig', array(
                    'page' => $page,
                    'blog_entries' => $blogEntries
        ));
    }

}
