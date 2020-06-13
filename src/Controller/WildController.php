<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Form\EpisodeType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\WildMailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

/**
 * @Route("/", name="wild_")
 */
class WildController extends AbstractController
{

    /**
     * @Route("", name="index")
     * @param ProgramRepository $programRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {

        $errors = '';
        $programs = $programRepository->getAllProgramsForHome();
        $homeProgram = $programRepository->getHomeProgramId();

        if (!$programs) {
            throw $this->createNotFoundException(
                'Aucune série trouvée'
            );
        }
        return $this->render('home.html.twig', [
            'errors'       => $errors,
            'home_program' => $homeProgram,
            'programs'     => $programs,
            'website'      => 'Wild Series',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}",
     *     requirements={"slug"="[a-z0-9\-]*"},
     *     defaults={"slug"="Aucune série selectionnée"},
     *     name="show")
     */
    public function show(string $slug, ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('Aucune série à rechercher');
        } else {
            $program = $programRepository->findOneBy(['slug' => $slug ]);
            }
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with the slug ' . $slug . ', found in program\'s table.'
            );
        }
        $categories = $categoryRepository->findAll();
            return $this->render('wild/show.html.twig', [
                'website'    => 'Wild Série',
                'program'    => $program,
                'categories' => $categories,
            ]);
    }

    /**
     * @Route("/wild/cat/{cat}",
     *     requirements={"cat"="[a-z\-]*"},
     *     defaults={"cat"="Aucune catégorie selectionnée"},
     *     name="cat")
     */
    public function showByCat(string $cat, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $cat = ucwords(str_replace('-', ' ', $cat));
        if (!$cat) {
            throw $this->createNotFoundException('Aucune catégorie dans laquelle rechercher');
        } else {
            $category = $categoryRepository->findOneByName($cat);
            $categories = $categoryRepository->findAll();
            $programs = $programRepository->findBy(
                ['category' => $category->getId()],
                ['id' => 'desc'],
                3,
            );
        }
        return $this->render('cat.html.twig', [
            'website'    => 'Wild Série',
            'programs'   => $programs,
            'cat'        => $cat,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/wild/season/{seasonId}",
     *     requirements={"seasonId"="[0-9]{1,}"},
     *     defaults={"seasonId"="1"},
     *     name="season")
     */
    public function showBySeason (int $seasonId, SeasonRepository $seasonRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $season = $seasonRepository->findOneById($seasonId);
        return $this->render('season.html.twig', [
            'website'    => 'Wild Série',
            'season'     => $season,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Episode $episode
     * @return Response
     * @Route("/wild/episode/{id}",
     *     requirements={"id"="[0-9]{1,}"},
     *     defaults={"id"="1"},
     *     name="episode")
     */
    public function showEpisode(Episode $episode): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('episode.html.twig', [
            'website'    => 'Wild Série',
            'episode'    => $episode,
            'form'       => $form->createView()
        ]);

    }
}
