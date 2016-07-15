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
    
    public function indexAction(Request $request, $tag, $page) {
        return $this->pageAction($request, $tag, $page);
    }
    
    public function pageAction(Request $request, $tag, $page) {
        $blogEntryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Entry');
        $blogCategoryRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Category');
        $blogTagRepository = $this->getDoctrine()
                ->getRepository('BlogBundle:Tag');
        $blogEntries = $blogEntryRepository->findAllByTagSlug($tag, $page);
        $blogCategories = $blogCategoryRepository->findAll();
        $blogTags = $blogTagRepository->findAll();
        if (empty($blogEntries)) {
            throw $this->createNotFoundException();
        }
        return $this->render('BlogBundle:Entries:Tag/page.html.twig', array(
                    'blog_categories' => $blogCategories,
                    'blog_tags' => $blogTags,
                    'blog_entries' => $blogEntries,
                    'tag_slug' => $tag,
                    'page' => $page,
        ));
    }

}
