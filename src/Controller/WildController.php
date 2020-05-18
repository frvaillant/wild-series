<?php


namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
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
    public function index(): Response
    {
        $programs = $this->getDoctrine()
                        ->getRepository(Program::class)
                        ->findAll();
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
    public function show(string $title): Response
    {
        $title = ucwords(str_replace('-', ' ', $title));
        if (!$title) {
            throw $this->createNotFoundException('Aucune série à rechercher');
        } else {
            $program = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findOneBy(['title' => $title ]);
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

        $category = $categoryRepository->findOneByName($cat);
        $programs = $programRepository->findBy(
            ['category' => $category->getId()],
            ['id' => 'asc'],
             3,
        );

        return $this->render('cat.html.twig', [
            'website' => 'Wild Série',
            'programs' => $programs,
            'cat'   => $cat,
        ]);
    }
}
