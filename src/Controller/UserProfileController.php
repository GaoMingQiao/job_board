<?php

namespace App\Controller;

use App\Services\UploadFiles;
use App\Entity\Profile;
use App\Form\ProfileType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class UserProfileController extends AbstractController
{

    #[Route('/user/profile', name: 'app_user_profile')]
    public function index(Request $request, EntityManagerInterface $em, UploadFiles $uploadFiles): Response
    {
        $userProfile = new Profile();

        $form = $this->createForm(ProfileType::class, $userProfile);
        $form->handleRequest($request);
        //  dd($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            // $user = $this->getUser(); 
            $userProfile->setUser($this->getUser()); //setter user d'abord
            $slugify = new Slugify();
            $userProfile->setSlug($slugify->slugify($userProfile->getFirstName() . ' ' .
                $userProfile->getLastName()));
            // dd($form['imageFile']->getData());

            $file = $form['imageFile']->getData();

            $file_name = $uploadFiles->saveFileUpload($file);
            $userProfile->setPicture($file_name);
            $em->persist($userProfile);
            $em->flush();
        }

        return $this->render('user_profile/index.html.twig', [
            'form' => $form,
        ]);
    }
}
