<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController
{
    #[Route('/patients')]
    public function index(): Response
    {
        $people = [
            [
                'name' => 'Karim',
                'birth_date' => '01/01/2000'
            ],
            [
                'name' => 'Alarmi',
                'birth_date' => '01/01/1990'
            ],
        ];

        return $this->render('patients/index.html.twig', [
            'people' => $people
        ]);
    }
}

