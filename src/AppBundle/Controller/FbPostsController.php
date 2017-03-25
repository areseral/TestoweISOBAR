<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class FbPostsController extends Controller
{
    /**
     * @Route("/fbposts", name="fbposts")
     */
    public function indexAction(Request $request)
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $productRepository = $entityManager->getRepository('AppBundle:FBposts');
        $array = $productRepository->getNewestPostsFB();
        
        return $this->render('default/posts.html.twig', [
            'posts' => $array
        ]);
        
    }
}
