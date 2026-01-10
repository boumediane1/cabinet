<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Speciality;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AppointmentController extends AbstractController
{
    #[Route('/appointments', name: 'app_appointments.index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $repo = $entityManager->getRepository(Appointment::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            $appointments = $repo->findAll();
        } elseif ($this->isGranted('ROLE_DOCTOR')) {
            $appointments = $repo->findBy(['doctor' => $user]);
        } else { // ROLE_PATIENT
            $appointments = $repo->findBy(['patient' => $user]);
        }

        return $this->render('appointments/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    #[Route('/appointments/create', name: 'app_appointments.create')]
    #[IsGranted('ROLE_PATIENT')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appointment = new Appointment();

        $form = $this->createForm(AppointmentType::class, $appointment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setPatient($this->getUser());

            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_appointments.index');
        }

        return $this->render('appointments/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/appointments/{id}/edit', name: 'app_appointments.edit')]
    public function edit(
        Appointment            $appointment,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($appointment->getPatient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($appointment->isConfirmed()) {
            throw $this->createAccessDeniedException('Confirmed appointments cannot be edited.');
        }

        $form = $this->createForm(AppointmentType::class, $appointment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_appointments.index');
        }

        return $this->render('appointments/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/appointments/{id}', name: 'app_appointments.delete', methods: ['POST'])]
    public function delete(
        Appointment            $appointment,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($appointment->getPatient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($appointment->isConfirmed()) {
            throw $this->createAccessDeniedException('Confirmed appointments cannot be deleted.');
        }

        if ($this->isCsrfTokenValid(
            'delete' . $appointment->getId(),
            $request->request->get('_token')
        )) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appointments.index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/appointments/{id}/confirm', name: 'app_appointments.confirm', methods: ['POST'])]
    public function confirm(
        Appointment $appointment,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
            'confirm' . $appointment->getId(),
            $request->request->get('_token')
        )) {
            $appointment->setConfirmed(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_appointments.index');
    }
}
