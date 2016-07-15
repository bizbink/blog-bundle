<?php

namespace bizbink\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * TagController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class TagController extends Controller {
    
    public function indexAction(Request $request, $slug) {
        return $this->pageAction($request, $slug, $page);
    }
    
    public function pageAction(Request $request, $slug) {
        $page = $request->get('page');
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntries = $blogEntryRepository->findAllByTagSlug($slug, $page);
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        $tag = $blogTagRepository->findOneBy(array('slug' => $slug));
        if (empty($blogEntries)) {
            throw $this->createNotFoundException(
                    'No entries found for page ' . $page
            );
        }
        return $this->render('BlogBundle:Entries:Tag/page.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'blog_entries' => $blogEntries,
                    'tag' => $tag,
                    'tag_slug' => $slug,
                    'page' => $page,
        ));
    }

}
