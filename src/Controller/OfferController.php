<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use Cocur\Slugify\Slugify;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
//ou  #[is_granted('ROLE_PRO')] pour les professionnels
#[Route('/account')]
class OfferController extends AbstractController
{    //cette methode permet de lister tous les offers
    #[Route('/offer', name: 'app_offer')]
    public function index(OfferRepository $rep): Response
    {
        $entrepriseConnecte = $this->getUser()->getEntrepriseProfil();
        $offers = $rep->findBy(['entrepriseProfil' => $entrepriseConnecte]);

        return $this->render('offer/list.html.twig', [

            'offers' => $offers
        ]);
    }
    //cette route permet de créer 
    #[Route('/entreprise/offer/new', name: 'app_offer_create')]
    public function createNewOffer(Request $request, EntityManagerInterface $em): Response
    {
        $company = $this->getUser()->getEntrepriseProfil();
        // dd($user->getEntrepriseProfil());
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        } else {
            $offer = new Offer();

            $form = $this->createForm(OfferType::class, $offer);
            // dd($form);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $offer->setEntrepriseProfil($company);
                $em->persist($offer);
                $em->flush();
                return $this->redirectToRoute('app_offer');
            }

            return $this->render('offer/index.html.twig', [

                'form' => $form->createView()

            ]);
        }
    }
    // #[Route('/entreprise/offer/show/{slug}', name: 'app_offer_show')]

    // public function show(Offer $offer): Response
    // {



    //     return $this->render('offer/show.html.twig', [
    //         'offer' => $offer

    //     ]);
    // }

    #[Route('/entreprise/offer/show/{slug}', name: 'app_offer_show')]
    public function show(string $slug, OfferRepository $offerRepository, ApplicationRepository $applicationRepository): Response
    {
        $user = $this->getUser();

        $company = $user->getEntrepriseProfil();

        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }
        $offer = $offerRepository->findOneBy(['slug' => $slug]);

        if (!$offer) {
            return $this->redirectToRoute('app_offer');
        }
        // Vérifier que l'offre appartient bien à l'entreprise connectée
        if ($offer->getEntrepriseProfil() !== $company) {
            return $this->redirectToRoute('app_offer');
        }

        // Récupartions des candidatures

        $applications = $applicationRepository->findBy(['Offer' => $offer]);


        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'applications' => $applications

        ]);
    }

    #[Route('/entreprise/offer/{slug}/edit', name: 'app_offer_edit')]
    public function edit(Request $request, OfferRepository $rep, string $slug, EntityManagerInterface $em)
    {
        $company = $this->getUser()->getEntrepriseProfil();
        $offer = $rep->findOneBy(['slug' => $slug]);
        if ($offer->getEntrepriseProfil()->getName() !== $company->getName()) {
            return $this->redirectToRoute('app_offer_show', ['slug' => $offer->getSlug()]);
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->redirectToRoute('app_offer_show', ['slug' => $offer->getSlug()]);
        }
        return $this->render('offer/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/entreprise/offer/{slug}/delete', name: 'app_offer_delete')]
    public function delete(Request $request, OfferRepository $rep, string $slug, EntityManagerInterface $em, TokenStorageInterface $tokenStorageInterface, SessionInterface $session)
    {
        $company = $this->getUser()->getEntrepriseProfil();
        $offer = $rep->findOneBy(['slug' => $slug]);
        if ($offer->getEntrepriseProfil()->getName() !== $company->getName()) {
            return $this->redirectToRoute('app_offer');
        }

        $em->remove($offer);
        $em->flush();
        $tokenStorageInterface->setToken(null);
        $session->invalidate();
        return  $this->redirectToRoute('app_offer');
    }
}
