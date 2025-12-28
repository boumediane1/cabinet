<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/greeting')]
    public function number(): Response
    {
        return $this->render('lucky.html.twig', [
            'people' => [
                [
                    'name' => 'Nisrine',
                    'profession' => 'Student',
                    'age' => 18
                ],
                [
                    'name' => 'Hajar',
                    'profession' => 'Student',
                    'age' => 24
                ]
            ]
        ]);
    }
}
