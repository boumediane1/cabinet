<?php

namespace App\Controller;

use App\Entity\Visit;
use App\Entity\Doctor;
use App\Form\VisitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class VisitController extends AbstractController
{
    #[Route('/visits', name: 'app_visits.index')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $repo = $em->getRepository(Visit::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            $visits = $repo->findAll();
        } elseif ($this->isGranted('ROLE_DOCTOR')) {
            $visits = $repo->findBy([
                'doctor' => $user,
            ]);
        } else { // ROLE_PATIENT
            $visits = $repo->findBy([
                'patient' => $user,
            ]);
        }

        return $this->render('visits/index.html.twig', [
            'visits' => $visits,
        ]);
    }

    // ===================== ADMIN ONLY =====================

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/visits/create', name: 'app_visits.create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $visit = new Visit();


        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($visit);
            $em->flush();

            return $this->redirectToRoute('app_visits.index');
        }

        return $this->render('visits/form.html.twig', [
            'form' => $form,
            'title' => 'Create Visit',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/visits/{id}/edit', name: 'app_visits.edit')]
    public function edit(
        Visit $visit,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_visits.index');
        }

        return $this->render('visits/form.html.twig', [
            'form' => $form,
            'title' => 'Edit Visit',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/visits/{id}', name: 'app_visits.delete', methods: ['POST'])]
    public function delete(
        Visit $visit,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid(
            'delete' . $visit->getId(),
            $request->request->get('_token')
        )) {
            $em->remove($visit);
            $em->flush();
        }

        return $this->redirectToRoute('app_visits.index');
    }
}
