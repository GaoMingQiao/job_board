<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function index(Request $request): Response
    {
        $userProfile = new Profile();
        $form = $this->createForm(ProfileType::class, $userProfile);
        $form->handleRequest($request);

        return $this->render('user_profile/index.html.twig', [
            'form' => $form,
        ]);
    }
}
