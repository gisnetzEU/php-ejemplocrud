<?php

namespace App\Controller;

use App\Entity\Casa;
use App\Form\CasaType;
use App\Repository\CasaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/casa')]
class CasaController extends AbstractController
{
    #[Route('/', name: 'casa_index', methods: ['GET'])]
    public function index(CasaRepository $casaRepository): Response
    {
        return $this->render('casa/index.html.twig', [
            'casas' => $casaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'casa_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $casa = new Casa();
        $form = $this->createForm(CasaType::class, $casa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($casa);
            $entityManager->flush();

            return $this->redirectToRoute('casa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('casa/new.html.twig', [
            'casa' => $casa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'casa_show', methods: ['GET'])]
    public function show(Casa $casa): Response
    {
        return $this->render('casa/show.html.twig', [
            'casa' => $casa,
        ]);
    }

    #[Route('/{id}/edit', name: 'casa_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Casa $casa): Response
    {
        $form = $this->createForm(CasaType::class, $casa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('casa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('casa/edit.html.twig', [
            'casa' => $casa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'casa_delete', methods: ['POST'])]
    public function delete(Request $request, Casa $casa): Response
    {
        if ($this->isCsrfTokenValid('delete'.$casa->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($casa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('casa_index', [], Response::HTTP_SEE_OTHER);
    }
}
