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
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntries = $blogEntryRepository->findAllByCategoryName($category, $page);
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
