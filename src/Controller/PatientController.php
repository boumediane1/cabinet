<?php

namespace App\Controller;

use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController
{
    #[Route('/patients', name: 'patients.index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $patients = $entityManager->getRepository(Patient::class)->findAll();

        return $this->render('patients/index.html.twig', [
            'patients' => $patients
        ]);
    }

    #[Route('/patients/create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = new Patient();

        $form = $this->createFormBuilder($patient)
            ->add('name', TextType::class)
            ->add('birth_date', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Add patient'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('patients.index');
        }

        return $this->render('patients/create.html.twig', [
            'form' => $form
        ]);
    }
}
