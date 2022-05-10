<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PinRepository $repo): Response
    {
        $pins = $repo->findAll();
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    #[Route('/pin/create', methods: ("GET"), name: "app_pins_create")]
    public function create()
    {
        return $this->render('pins/create.html.twig');
    }

    #[Route('/pin/store', methods: ("POST"))]
    public function store(Request $request, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('pins_create', $request->request->get('token'))) {
            $pin = new Pin;
            $pin->setTitle($request->request->get('title'));
            $pin->setDescription($request->request->get('description'));
            $em->persist($pin);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('app_home'));
    }
}
