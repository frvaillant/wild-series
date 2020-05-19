<?php


namespace App\Controller;

use App\Entity\Episode;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Program;

/**
 * @Route("/", name="wild_")
 */
class WildController extends AbstractController
{

    /**
     * @Route("", name="index")
     */
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'Aucune série trouvée'
            );
        }

        return $this->render('home.html.twig', [
            'programs' => $programs,
            'website'  => 'Wild Series',
        ]);
    }

    /**
     * @Route("/wild/show/{title}",
     *     requirements={"title"="[a-z0-9\-]*"},
     *     defaults={"title"="Aucune série selectionnée"},
     *     name="show")
     */
    public function show(string $title, ProgramRepository $programRepository): Response
    {
        $title = ucwords(str_replace('-', ' ', $title));
        if (!$title) {
            throw $this->createNotFoundException('Aucune série à rechercher');
        } else {
            $program = $programRepository->findOneBy(['title' => $title ]);
            }
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $title . ' title, found in program\'s table.'
            );
        }
            return $this->render('show.html.twig', [
                'website' => 'Wild Série',
                'program' => $program,
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
            $programs = $programRepository->findBy(
                ['category' => $category->getId()],
                ['id' => 'desc'],
                3,
            );
        }
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in ' . $cat . ' category'
            );
        }

        return $this->render('cat.html.twig', [
            'website' => 'Wild Série',
            'programs' => $programs,
            'cat'   => $cat,
        ]);
    }

    /**
     * @Route("/wild/season/{seasonId}",
     *     requirements={"seasonId"="[0-9]{1,}"},
     *     defaults={"seasonId"="1"},
     *     name="season")
     */
    public function showBySeason (int $seasonId, SeasonRepository $seasonRepository): Response
    {
        $season = $seasonRepository->findOneById($seasonId);
        return $this->render('season.html.twig', [
            'website' => 'Wild Série',
            'season' => $season,
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
        //$episode->getSeason();  PAS BESOIN
        //$season->getProgram();  PAS BESOIN NON PLUS

        return $this->render('episode.html.twig', [
            'website' => 'Wild Série',
            'episode' => $episode,
        ]);

    }
}
