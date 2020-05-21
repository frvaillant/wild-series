<?php


namespace App\Controller\admin;


use App\Entity\Category;
use App\Form\CategoryCreateFormType;
use App\Repository\CategoryRepository;
use App\Service\AdminMessages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{

    /**
     * @param Request $request
     * @param string $message
     * @return Response
     * @Route("/index/{message}",
     *     requirements={"message"="[a-zA-Z0-9_-]{1,}"},
     *     defaults={"message"=""},
     *     name="home")
     */
    public function index(CategoryRepository $categoryRepository, Request $request, string $message): Response
    {
        $category = new Category();
        $formCat = $this->createForm(
            CategoryCreateFormType::class,
            $category,
            ['method' => Request::METHOD_POST]
        );
        $formCat->handleRequest($request);

        if ($formCat->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $actionMessage = 'add_category_' . $category->getName();
            $actionMessage = AdminMessages::prepareMessage($actionMessage);
            return $this->redirectToRoute('admin_home', [
                'message' => $actionMessage
            ]);
        }

        $messageMaker = new AdminMessages($message);
        $message = $messageMaker->makeMessage();

        $categories = $categoryRepository->findAll();
        return $this->render('admin/admin.html.twig', [
            'website' => 'Wild Série',
            'message' => $message,
            'form_cat' => $formCat->createView(),
            'categories' => $categories,
        ]);
    }

}