<?php

namespace Modera\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ModeraDemoBundle:Default:index.html.twig');
    }
    
    public function treeAction($filename)
    {
        $path = $this->get('kernel')->locateResource("@ModeraDemoBundle/Data/{$filename}.txt");
        // create a JSON-response with a 200 status code
        $json = $this->get('app.rawtojson')->convert($path);
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json'); 
        return $response;
    }    
}
