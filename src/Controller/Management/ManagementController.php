<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use App\Entity\Client;
use Symfony\Component\Routing\Annotation\Route;

class ManagementController extends Controller
{
    /**
     * index()
     *
     * @Route("/management", name="management-home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        // Pull a list of all the current clients
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findBy([
                "deleted" => null
            ]);

        return $this->render('pages/management/management.html.twig', [
            "pageTitle" => "Management",
            "pageDescription" => "User client & services manager",
            "pageKeywords" => "management, services, users, frontends",
            "bodyClass" => "management-home-page",
            "clients" => $clients
        ]);
    }
}
