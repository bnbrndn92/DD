<?php

namespace App\Controller;

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

    /**
     * createClient()
     *
     * @Route("/management/client/create", name="management-create-client")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createClient ()
    {
        return $this->render('pages/management/create-client.html.twig', [
            "pageTitle" => "Create Client",
            "pageDescription" => "Management create client page",
            "pageKeywords" => "management, create, client",
            "bodyClass" => "management-create-client-page",
        ]);
    }

    /**
     * viewClient()
     *
     * @Route("/management/client/{id}", name="management-view-client", requirements={"id"="\d+"})
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewClient (int $id)
    {
        return $this->render('pages/management/view-client.html.twig', [
            "pageTitle" => "Client Name",
            "pageDescription" => "Client page",
            "pageKeywords" => "management, client",
            "bodyClass" => "management-client-page"
        ]);
    }

    /**
     * viewService()
     *
     * @Route("/management/service/{id}", name="management-view-service", requirements={"id"="\d+"})
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewService (int $id)
    {
        return $this->render('pages/management/view-service.html.twig', [
            "pageTitle" => "Service Name",
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page"
        ]);
    }
}
