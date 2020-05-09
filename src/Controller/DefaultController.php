<?php


namespace App\Controller;

use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    /**
     * @return string
     */
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $message = 'Bienvenue sur ma page';
        return $this->render('index.html.twig', [
            'message' => $message
        ]);
    }

}