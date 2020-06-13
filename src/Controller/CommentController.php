<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/{episode}", name="comment_index", methods={"GET"})
     */
    public function index(EpisodeRepository $episodeRepository, CommentRepository $commentRepository, $episode): Response
    {
        $theEpisode = $episodeRepository->findOneById($episode);
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findBy(['episode' => $theEpisode], ['id' => 'DESC']),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new/{episode}", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request, EpisodeRepository $episodeRepository, $episode): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $theEpisode = $episodeRepository->findOneById($episode);
            $comment->setEpisode($theEpisode);
            $user = $this->getUser();
            $comment->setAuthor($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('wild_episode', [
                'id' => $episode
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
