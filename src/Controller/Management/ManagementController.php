<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use App\Entity\Client;
use App\Entity\Frontend;
use App\Traefik\Bandwidth;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ManagementController extends Controller
{
    /**
     * ManagementController::index
     *
     * @Route("/management", name="management-index")
     *
     * @return Response
     */
    public function index() : Response
    {
        // Pull a list of all the current clients
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();

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
