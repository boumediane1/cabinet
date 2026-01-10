<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PatientController extends AbstractController
{
    #[Route('/patients', name: 'app_patients.index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $patients = $entityManager->getRepository(Patient::class)->findAll();

        return $this->render('patients/index.html.twig', [
            'patients' => $patients
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/patients/{id}/edit', name: 'app_patients.edit')]
    public function edit(
        Patient $patient,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(PatientRegistrationType::class, $patient, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $patient,
                    $plainPassword
                );
                $patient->setPassword($hashedPassword);
            }

            $em->flush();

            return $this->redirectToRoute('app_patients.index');
        }

        return $this->render('patients/edit.html.twig', [
            'form' => $form,
            'patient' => $patient,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/patients/{id}', name: 'app_patients.delete', methods: ['POST'])]
    public function delete(Patient $patient, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->request->get('_token'))) {
            $em->remove($patient);
            $em->flush();
        }

        return $this->redirectToRoute('app_patients.index');
    }
}
