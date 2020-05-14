<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="wild")
 */
class WildController extends AbstractController
{

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig', [
            'website' => 'Wild Série',
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'website' => 'Wild Série',
        ]);
    }



    /**
     * @Route("wild/show/{title}",
     *     requirements={"title"="[a-z0-9\-]*"},
     *     defaults={"title"="Aucune série selectionnée"},
     *     name="show")
     */
    public function show(string $title): Response
    {
        $title = ucwords(str_replace('-', ' ', $title));
        return $this->render('show.html.twig', [
            'website' => 'Wild Série',
            'title' => $title,
        ]);

    }


}