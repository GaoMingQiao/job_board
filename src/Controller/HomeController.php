<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Offer;
use App\Entity\Application;
use App\Entity\HomeSetting;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityManager;
use App\Entity\EntrepriseProfil;
use App\Form\StatusType;
use App\Repository\TagRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EntrepriseProfilRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em, OfferRepository $offerRepository, EntrepriseProfilRepository $entrepriseProfilRepository): Response
    {
        $settings = $em->getRepository(HomeSetting::class)->findAll();

        $offers = $offerRepository->findBy([], ['createdAt' => 'DESC'], 6);
        // dd($offers);
        $entreprises = $entrepriseProfilRepository->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'offers' => $offers,
            'entreprises' => $entreprises,
        ]);
    }


    #[Route('/offer-job/{slug}', name: 'app_offre_emploi_show')]
    public function offreEmploiShow($slug, OfferRepository $offerRepository, ApplicationRepository $applicationRepository, EntityManagerInterface $em, Request $request, TagRepository $tagRepository): Response
    {
        $user = $this->getUser();
        $tags = $tagRepository->findAll();

        $offer = $offerRepository->findOneBy(['slug' => $slug]);

        if (!$offer) {
            throw $this->createNotFoundException("L'offre demandée n'existe pas");
        }

        $entreprise = $offer->getEntrepriseProfil();

        $existingsApplication = $applicationRepository->findOneBy(
            ['Offer' => $offer, 'User' => $user, 'Entreprise' => $entreprise],
        );

        if ($existingsApplication) {
            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->addWarning('Vous avez déjà postulé à cette offre');
        }


        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $application->setUser($user);
            $application->setOffer($offer);
            $application->setEntreprise($offer->getEntrepriseProfil());
            $application->setCreatedAt(new \DateTimeImmutable());
            $application->setMessage($form->get('message')->getData());
            $application->setStatus('STATUS_PENDING');
            $em->persist($application);
            $em->flush();
        }

        return $this->render('home/offer-emploi-show.html.twig', [
            'Offer' => $offer,
            'form' => $form->createView(),
            'existingsApplication' => $existingsApplication,
        ]);
    }
    #[Route('/offer-emploi', name: 'app_offer_emploi')]
    public function offreEmploi(OfferRepository $offerRepository, TagRepository $tagRepository, Request $request): Response
    {
        // $offers = $offerRepository->findBy([], ['id' => 'DESC']);


        $tags = $tagRepository->findAll();
        $page = $request->query->get('page', 1); //le chiffre de page est passé par url
        $offers = $offerRepository->findPaginatedOffers($page, 8);
        // dd($offers);

        return $this->render('home/offer-emploi.html.twig', [
            'offers' => $offers,
            'tags' => $tags
        ]);
    }

    // #[Route('/entreprise/offer/{slug}/candidate/{id}', name: 'app_offre_candidate')]
    // public function getCandidate(string $id, string $slug, Request $request, EntityManagerInterface $em,  OfferRepository $offerRepository, ApplicationRepository $applicationRepository): Response
    // {
    //     $offer = $offerRepository->findOneBy(['slug' => $slug]);

    //     $candidate = $applicationRepository->findOneBy(['id' => $id]);

    //     $form = $this->createForm(StatusType::class, $candidate);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $em->flush();

    //         notyf()
    //             ->position('x', 'right')
    //             ->position('y', 'top')
    //             ->addSuccess('La candidature a bien été modifiée.');

    //         return $this->redirectToRoute('app_offer_show', ['slug' => $offer->getSlug()]);
    //     }

    //     return $this->render(
    //         'home/1.html.twig',
    //         [
    //             'candidate' => $candidate,
    //             'statusForm' => $form->createView()
    //         ]
    //     );
    // }

    #[Route('offer-emploi/status/{id}', name: 'app_offer_status')]
    public function status($id, OfferRepository $offerRepository, ApplicationRepository $applicationRepository, EntityManagerInterface $em, Request $request)
    {
        // $user = $this->getUser();
        // $offer = $offerRepository->findOneBy(['slug' => $slug]);
        // $entreprise = $offer->getEntrepriseProfil();
        // $application = $applicationRepository->findOneBy(
        //     ['offer' => $offer, 'user' => $user, 'entreprise' => $entreprise],
        // );
        $application = $applicationRepository->find($id);
        // $status = $application->getStatus();

        $form = $this->createForm(StatusType::class, $application);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }




        return $this->render('home/1.html.twig', [
            'statusForm' => $form->createView(),
            'application' => $application

        ]);
    }
}
