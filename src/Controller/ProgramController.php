<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use App\Service\WildMailer;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_index", methods={"GET"})
     */
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findAll(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="program_new", methods={"GET","POST"})
     */
    public function new(WildMailer $mailer, Request $request, CategoryRepository $categoryRepository, ValidatorInterface $validator, Slugify $slugify): Response
    {
        $categories = $categoryRepository->findAll();
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $program->setSlug($slugify->generate($program->getTitle()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            $this->addFlash('success', 'la série ' . $program->getTitle() . ' a bien été ajoutée');
            $mailer->sendMailNewProgram($program->getTitle(), $program->getSummary(), $program->getSlug());

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="program_show", methods={"GET"})
     */
    public function show(Program $program, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="program_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="program_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Program $program, CategoryRepository $categoryRepository): Response
    {

        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $title = $program->getTitle();
            $entityManager->remove($program);
            $entityManager->flush();
            $this->addFlash('error', 'la série ' . $title . ' a bien été supprimée');
        }

        return $this->redirectToRoute('program_index');
    }

    /**
     * @Route("/{program}/watchlist", name="program_watchlist", methods={"GET"})
     */
    public function addToWatchList ($program, ProgramRepository $programRepository): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $program = $programRepository->findOneById($program);

        if ($this->getUser()->getPrograms()->contains($program)) {
            $this->getUser()->removeProgram($program);
            $message = $program->getTitle() . ' a été supprimé de votre watch list';
        }
        else {
            $this->getUser()->addProgram($program);
            $message = $program->getTitle() . ' a été ajouté à votre watch list';
        }
        $entityManager->flush();
        return $this->json([
            'message'       => $message
        ]);
    }
}
