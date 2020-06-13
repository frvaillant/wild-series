<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/profile", name="user_", methods={"GET","POST"})
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile", methods={"GET","POST"})
     */
    public function profile (): Response
    {
        $user = $this->getUser();
        return $this->render('user/profile.html.twig', [
            'username' => $user->getUsername(),
            'email'    => $user->getEmail(),
        ]);

    }

}
