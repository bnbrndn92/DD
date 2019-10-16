<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use App\Entity\Client;
use App\Entity\Frontend;
use App\Traefik\Bandwidth;
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

        // Pull a list of all distinct frontends from the Traefik DB
        $traefikFrontends = $this->getDoctrine()
            ->getRepository(Bandwidth::class)
            ->findUniqueFrontends();

        if (empty($traefikFrontends)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, error",
                "bodyClass" => "error-page",
                "message" => "No Traefik bandwidth data found",
            ]);
        }

        // Attempt to pull out the frontends
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        // Remove assigned frontends
        foreach ($traefikFrontends as $key => $unassignedFrontend) {
            $frontend = $frontendRepo->findBy([
                "name" => $unassignedFrontend,
                "deleted" => null,
            ]);

            // Encode the frontend
            $traefikFrontends[$key]['encoded'] = urlencode($traefikFrontends[$key]['frontend']);

            if (!empty($frontend)) {
                unset($traefikFrontends[$key]);
            }
        }

        return $this->render('pages/management/management.html.twig', [
            "pageTitle" => "Management",
            "pageDescription" => "User client & services manager",
            "pageKeywords" => "management, services, users, frontends",
            "bodyClass" => "management-home-page",
            "clients" => $clients,
            "unassignedFrontends" => $traefikFrontends,
        ]);
    }
}
