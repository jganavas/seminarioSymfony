<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/about', name: 'app_about', methods: ['GET'])]
    public function about(): Response
    {
        $team = [
            [
                'name' => 'Sr. Whiskers',
                'position' => 'CEO & Founder',
                'bio' => 'Con 9 vidas de experiencia en el sector felino, el Sr. Whiskers fundó esta empresa tras una reveladora siesta de 16 horas. Siempre impecable con su traje de tres piezas, lidera la empresa con una combinación única de elegancia y caos controlado. Especialista en estrategia de alto nivel y en derribar objetos importantes durante reuniones ejecutivas.',
                'image' => 'https://cataas.com/cat?width=300&height=300&type=square&t=ceo'
            ],
            [
                'name' => 'Doña Mittens',
                'position' => 'Manager de Operaciones',
                'bio' => 'Graduada con honores de la Universidad Gatuna, Doña Mittens coordina todos los ronroneos del departamento. Prefiere la comodidad de un jersey casual para sus largas jornadas organizando el caos diario. Su habilidad para aterrizar siempre de pie la hace indispensable en situaciones de crisis.',
                'image' => 'https://cataas.com/cat?width=300&height=300&type=square&t=manager'
            ],
            [
                'name' => 'Felix el Veloz',
                'position' => 'Desarrollador Senior',
                'bio' => 'Experto en perseguir bugs y punteros del mouse. Con su sudadera con capucha característica, Felix es el ninja del código nocturno. Tiene un máster en Cajas de Cartón y Espacios Pequeños. Trabaja mejor entre las 3AM y 5AM sin razón aparente, siempre con música lo-fi de fondo.',
                'image' => 'https://cataas.com/cat?width=300&height=300&type=square&t=dev'
            ],
            [
                'name' => 'Luna Peluda',
                'position' => 'Diseñadora UX/UI',
                'bio' => 'Especialista en crear interfaces que brillan en la oscuridad. Su estilo hipster casual con gafas redondas la distingue en la oficina. Le apasiona el diseño minimalista, especialmente cuando implica empujar cosas fuera del escritorio para "simplificar". Insiste en que Comic Sans nunca fue una buena idea.',
                'image' => 'https://cataas.com/cat?width=300&height=300&type=square&t=designer'
            ],
        ];

        return $this->render('blog/about.html.twig', [
            'team' => $team,
        ]);
    }
}
