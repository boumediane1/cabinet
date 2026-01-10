<?php

namespace App\Controller;

use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $patient = new Patient();

        $form = $this->createFormBuilder($patient)
            ->add('name', TextType::class)
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female'
                ],
                'expanded' => true
            ])
            ->add('birth_date', DateType::class)
            ->add('address', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $patient,
                $patient->getPassword()
            );

            $patient->setPassword($hashedPassword);
            $patient->setRoles(['ROLE_PATIENT']);

            $entityManager->persist($patient);
            $entityManager->flush();

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_patients.index');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register.html.twig', [
            'form' => $form
        ]);
    }
}
