<?php

namespace bizbink\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * DefaultController
 * 
 * @author Matthew Vanderende <matthew@vanderende.ca>
 */
class DefaultController extends Controller {

    /**
     * @Route("/", defaults={"page" = 1}, name="blog")
     */
    public function indexAction(Request $request, $page) {

        return $this->forward('BlogBundle:Entries:page', array(
                    'page' => $page,
                        ), array(
                    '_route' => $request->attributes->get('_route')
        ));
    }

}
