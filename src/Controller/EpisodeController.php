<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use App\Form\EpisodeType;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/season/{id}", name="episode_index", methods={"GET"})
     */
    public function index(int $id, CategoryRepository $categoryRepository): Response
    {
        $season = $this->getDoctrine()->getRepository(Season::class)->findOneById($id);
        $categories = $categoryRepository->findAll();
        return $this->render('episode/index.html.twig', [
            'season' => $season,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new/{id}", name="episode_new", methods={"GET","POST"})
     */
    public function new(int $id, Request $request, CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        $season = $this->getDoctrine()->getRepository(Season::class)->findOneById($id);

        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episode->setSeason($season);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($episode);
            $entityManager->flush();
            return $this->redirectToRoute('episode_index', ['id' => $id ]);
        }

        return $this->render('episode/new.html.twig', [
            'season' => $season,
            'episode' => $episode,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="episode_show", methods={"GET"})
     */
    public function show(Episode $episode, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="episode_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Episode $episode, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="episode_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index');
    }
}
