<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class NavController extends AbstractController
{

    public function selectCategories (CategoryRepository $categoryRepository, $isActive)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('_components/_select_categories.html.twig', [
            'categories' => $categories,
            'active'     => $isActive,
        ]);
    }

}
