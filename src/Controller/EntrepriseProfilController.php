<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use App\Entity\EntrepriseProfil;
use App\Form\EntrepriseProfilType;
use App\Repository\EntrepriseProfilRepository;
use App\Services\UploadFiles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/account')]
class EntrepriseProfilController extends AbstractController
{
    #[Route('/entreprise/profil', name: 'app_entreprise_profil')]
    public function index(Request $request, EntityManagerInterface $em, UploadFiles $uploadFiles): Response
    {
        //ajouter ca après avoir cree la formulaire entreprise form; repuperer user connecté
        //il ajoute cette ligne après le formulaire est cree
        $user = $this->getUser();
        if ($user->getEntrepriseProfil()) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $user->getEntrepriseProfil()->getSlug()]);
        }

        $entrepriseProfil = new EntrepriseProfil();
        $slugify = new Slugify();
        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData()); //pour voir si je recois tous les data
            $entrepriseProfil->setUser($user);
            $entrepriseProfil->setSlug($slugify->slugify($entrepriseProfil->getName()));

            $file = $form['logoEntreprise']->getData();
            //if image est chargé
            if ($file) {
                $fileName = $uploadFiles->saveFileUpload($file);
                $entrepriseProfil->setLogo($fileName);
            }

            $em->persist($entrepriseProfil);
            $em->flush();
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $entrepriseProfil->getSlug()]);
        }


        return $this->render('entreprise_profil/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // #[Route('/entreprise/profil/{slug}/edit', name: 'app_entreprise_profil_edit')]
    // public function edit($slug,EntrepriseProfilRepository $entrepriseProfilRepo):Response{
    //     $user = $this->getUser();
    //     $entrepriseProfil = $entrepriseProfilRepo->findOneBy(['slug'=>$slug])
    // }
    #[Route('/entreprise/profil/{slug}', name: 'app_entreprise_profil_show')]
    public function show(EntrepriseProfil $entrepriseProfil): Response
    {


        return $this->render('entreprise_profil/show.html.twig', [
            'entrepriseProfile' => $entrepriseProfil
        ]);
    }
    #[Route('/entreprise/profil/{slug}/edit', name: 'app_entreprise_profil_edit')]
    public function editEntrepriseProfil(UploadFiles $uploadFiles, EntrepriseProfilRepository $rep, Request $request, EntityManagerInterface $em, $slug)
    {
        $user = $this->getUser();
        $entrepriseProfil = $rep->findOneBy(['slug' => $slug]);
        if (!$entrepriseProfil || $entrepriseProfil->getUser() !== $user) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $entrepriseProfil->getSlug()]);
        }


        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['logoEntreprise']->getData();
            if ($file) {
                $fileName = $uploadFiles->updateFileUpload($file, $entrepriseProfil->getLogo());
                $entrepriseProfil->setLogo($fileName);
            }

            $em->flush();
            $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $entrepriseProfil->getSlug()]);
        }
        return $this->render('entreprise_profil/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/entreprise/profil/{slug}/delete', name: 'app_entreprise_profil_delete')]
    public function delete(UploadFiles $uploadFiles, EntrepriseProfilRepository $rep, Request $request, EntityManagerInterface $em, $slug, TokenStorageInterface $tokenStorageInterface, SessionInterface $session)
    {
        $user = $this->getUser();
        $entrepriseProfil = $rep->findOneBy(['slug' => $slug]);
        if (!$entrepriseProfil || $entrepriseProfil->getUser() !== $user) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $entrepriseProfil->getSlug()]);
        }

        $uploadFiles->deleteFileUpload($entrepriseProfil->getLogo());
        $em->remove($entrepriseProfil);
        $em->flush();
        $tokenStorageInterface->setToken(null);
        $session->invalidate();
        return $this->redirectToRoute('app_home');
    }
}
