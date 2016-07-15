<?php

namespace bizbink\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * CategoryController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class CategoryController extends Controller {
    
    public function indexAction(Request $request, $slug) {
        return $this->pageAction($request, $slug);
    }
    
    public function pageAction(Request $request, $slug) {
        $page = $request->get('page');
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntries = $blogEntryRepository->findAllByCategorySlug($slug, $page);
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        $category = $blogCategoryRepository->findOneBy(array('slug' => $slug));
        if (empty($blogEntries)) {
            throw $this->createNotFoundException(
                    'No entries found for page ' . $page
            );
        }
        return $this->render('BlogBundle:Entries:Category/page.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'blog_entries' => $blogEntries,
                    'category' => $category,
                    'category_slug' => $slug,
                    'page' => $page,
        ));
    }

}
