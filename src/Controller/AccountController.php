<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user->getRoles()[0] === 'ROLE_PRO' && $user->getEntrepriseProfil() !== null) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $user->getEntrepriseProfil()->getSlug()]);
        }
        if ($user->getRoles()[0] === 'ROLE_USER' && $user->getProfile() !== null) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $user->getProfile()->getSlug()]);
        }
        // if ($user->getRoles()[0] === 'ROLE_ADMIN' && $user->getProfile() !== null) {
        //     return $this->redirectToRoute('admin');
        // }
        return $this->render('account/index.html.twig');
    }
}
