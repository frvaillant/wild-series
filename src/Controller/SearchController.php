<?php


namespace App\Controller;

use App\Entity\Episode;
use App\Form\SelectorType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search_index", methods={"GET","POST"})
     */
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $form = $this->createForm(SelectorType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('program_index');
        }
        return $this->render('search/index.html.twig', [
            'categories' => $categories,
            'form'       => $form->createView()
        ]);
    }

    /**
     * @Route("/addseason", name="search_update_prog", methods={"GET","POST"})
     */
    public function addSeason(Request $request): Response
    {
        $form = $this->createForm(SelectorType::class);

        $form->handleRequest($request);
        return $this->render('search/_form_search.html.twig', [
            'form'       => $form->createView()
        ]);
    }

    /**
     * @Route("/addepisodes", name="search_update_season", methods={"GET","POST"})
     */
    public function addEpisodes(Request $request): Response
    {
        $form = $this->createForm(SelectorType::class);
        $form->handleRequest($request);
        return $this->render('search/_form_search.html.twig', [
            'form'       => $form->createView()
        ]);
    }

    /**
     * @Route("/results/{id}", name="search_results", methods={"GET","POST"})
     * @param Episode $episode
     * @return Response
     */
    public function results(Episode $episode): Response
    {
        return $this->render('search/_results.html.twig', [
            'episode'       => $episode
        ]);
    }

}
