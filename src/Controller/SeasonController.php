<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\CategoryRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/season")
 */
class SeasonController extends AbstractController
{
    /**
     * @Route("/{seasonId}",
     *     requirements={"seasonId"="[0-9]{1,}"},
     *     defaults={"seasonId"=null},
     *     name="season_index", methods={"GET"})
     */
    public function index(?int $seasonId, SeasonRepository $seasonRepository, CategoryRepository $categoryRepository): Response
    {
        if (is_null($seasonId)){
            $seasons = $seasonRepository->findAll();
        }
        else {
            $seasons = $seasonRepository->findBy(['program' => $seasonId]);
        }
        $categories = $categoryRepository->findAll();
        return $this->render('season/index.html.twig', [
            'seasons' => $seasons,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="season_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $season = new Season();
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($season);
            $entityManager->flush();

            return $this->redirectToRoute('season_index');
        }

        return $this->render('season/new.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/show/{id}", name="season_show", methods={"GET"})
     */
    public function show(Season $season, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('season/show.html.twig', [
            'season' => $season,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{id}/edit", name="season_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Season $season, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('season_index');
        }

        return $this->render('season/edit.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="season_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Season $season): Response
    {

        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($season);
            $entityManager->flush();
        }

        return $this->redirectToRoute('season_index');
    }
}
