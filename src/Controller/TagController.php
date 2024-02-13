<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class TagController extends AbstractController
{
    #[Route('/tag', name: 'app_tag')]
    public function index(Request $request, TagRepository $tagRepository, EntityManagerInterface $em): Response
    {
        // Récupère les tags par ordre alphabétique
        $data = $tagRepository->findBy([], ['name' => 'ASC']);

        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            return $this->redirectToRoute('app_tag');
        }

        return $this->render('tag/index.html.twig', [
            'tags' => $data,
            'form' => $form->createView()
        ]);
    }
}
