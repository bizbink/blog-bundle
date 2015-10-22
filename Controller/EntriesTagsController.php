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
        $blogEntries = $blogEntryRepository->findAllByTagName($tag, $page);
        if (empty($blogEntries)) {
            throw $this->createNotFoundException();
        }
        return $this->render('BlogBundle:Entries:page.html.twig', array(
                    'page' => $page,
                    'blog_entries' => $blogEntries
        ));
    }

}
