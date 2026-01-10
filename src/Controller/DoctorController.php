<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/doctors')]
#[IsGranted('ROLE_ADMIN')]
class DoctorController extends AbstractController
{
    #[Route('/', name: 'app_doctors.index')]
    public function index(DoctorRepository $doctorRepository): Response
    {
        return $this->render('doctors/index.html.twig', [
            'doctors' => $doctorRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'doctors.create')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $doctor = new Doctor();
        $doctor->setRoles(['ROLE_DOCTOR']);

        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $doctor,
                $form->get('password')->getData()
            );

            $doctor->setPassword($hashedPassword);

            $em->persist($doctor);
            $em->flush();

            return $this->redirectToRoute('app_doctors.index');
        }

        return $this->render('doctors/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'doctors.edit')]
    public function edit(
        Doctor $doctor,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(DoctorType::class, $doctor, [
            'is_edit' => true,
        ]);

        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('doctors.index');
        }

        return $this->render('doctors/edit.html.twig', [
            'form' => $form,
            'doctor' => $doctor,
        ]);
    }

    #[Route('/{id}', name: 'doctors.delete', methods: ['POST'])]
    public function delete(
        Doctor $doctor,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->request->get('_token'))) {
            $em->remove($doctor);
            $em->flush();
        }

        return $this->redirectToRoute('doctors.index');
    }
}
