<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Cocur\Slugify\Slugify;
use App\Entity\Application;
use App\Services\UploadFiles;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/account')]
class UserProfilController extends AbstractController
{

    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(Request $request, EntityManagerInterface $em, UploadFiles $uploadFiles): Response
    {
        // $user = $this->getUser();
        // if ($user->getProfile()) {
        //     return $this->redirectToRoute('app_user_profil_show', ['slug' => $user->getProfile()->getSlug()]);
        // }
        $profil = new Profile();

        $form = $this->createForm(ProfileType::class, $profil);
        $form->handleRequest($request);
        $slugify = new Slugify();
        //  dd($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            // $user = $this->getUser(); 
            $profil->setUser($this->getUser()); //setter user d'abord

            $profil->setSlug($slugify->slugify($profil->getFirstName() . ' ' .
                $profil->getLastName()));
            // dd($form['imageFile']->getData());

            $file = $form['imageFile']->getData();


            if ($file) {
                $file_name = $uploadFiles->saveFileUpload($file);
                $profil->setPicture($file_name);
            } else {
                $profil->setPicture('default.png');
            }
            $em->persist($profil);
            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été créé!');
        }

        return $this->render('user_profil/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/user/profil/{slug}', name: 'app_user_profil_show')]
    public function show(Profile $profile): Response
    {   //symfony param converteur
        return $this->render('user_profil/show.html.twig', [
            'profile' => $profile,
        ]);
    }
    //modification du profil utilisateur
    #[Route('/user/profil/{slug}/edit', name: 'app_user_profil_edit')]
    public function editProfile(EntityManagerInterface $em, ProfileRepository $rep, $slug, Request $request, UploadFiles $uploadFiles): Response
    {
        $user = $this->getUser();
        $profil = $rep->findOneBy(['slug' => $slug]);
        if ($user !== $profil->getUser()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $profil->getSlug()]);
        }



        $form = $this->createForm(ProfileType::class, $profil);
        $form->handleRequest($request);
        $slugify = new Slugify();

        if ($form->isSubmitted() && $form->isValid()) {
            $profil->setSlug($slugify->slugify($profil->getFirstName() . '' . $profil->getLastName() . '' . $profil->getId()) . '' . uniqid());

            $file = $form['imageFile']->getData();
            // dd($file);
            if ($file) {
                $file_name = $uploadFiles->updateFileUpload(
                    $file,
                    $profil->getPicture()
                );
                $profil->setPicture($file_name);
            }

            // $em->persist($profile); on peut supprimer, parce que l'entity existe déjà dans bd
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié!');
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $profil->getSlug()]);
        }

        return $this->render('user_profil/edit.html.twig', [
            'form' => $form->createView(),


        ]);
    }

    #[Route('/user/profil/{slug}/delete', name: 'app_user_profil_delete')]
    public function deleteUserProfil(EntityManagerInterface $em, ProfileRepository $rep, $slug, Request $request, UploadFiles $uploadFiles, SessionInterface $session, TokenStorageInterface $tolenStorageInterface)
    {
        $user = $this->getUser();
        $profil = $rep->findOneBy(['slug' => $slug]);

        if ($user !== $profil->getUser()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $profil->getSlug()]);
        }
        $uploadFiles->deleteFileUpload($profil->getPicture());
        $em->remove($profil);
        $em->flush();
        $tolenStorageInterface->setToken(null);
        $session->invalidate();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/user/candidatures', name: 'app_user_candidatures')]
    public function listCandidatures()
    {
        $user = $this->getUser();
        $candidatures = $user->getApplications();
        // dd($candidatures);
        // foreach ($candidatures as $c) {
        //     return $offer = $c->getOffer();
        //     dd($offer);
        // }



        return $this->render('user_profil/candidaturesList.html.twig', [

            'candidatures' => $candidatures,
        ]);
    }
}
