<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Frontend;
use App\Entity\Service;
use App\Repository\ClientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * TODO - Include auth checks
     *
     * @Route("/management/client/{id}", name="management-view-client", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewClient (Request $request, int $id)
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($id),
        ]);

        if (empty($client)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, client, error",
                "bodyClass" => "error-page",
                "message" => "No client data found",
            ]);
        }

        // Attempt to pull out the services
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $services = $serviceRepo->findBy([
            "client_id" => $client->getId(),
        ]);

        // Attempt to pull out the frontends
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontends = $frontendRepo->findBy([
            "client_id" => $client->getId(),
        ]);

        return $this->render('pages/management/view-client.html.twig', [
            "pageTitle" => ucwords($client->getName()),
            "pageDescription" => "Client page",
            "pageKeywords" => "management, client",
            "bodyClass" => "management-client-page",
            "client" => $client,
            "services" => (!empty($services)) ? $services : null,
            "frontends" => (!empty($frontends)) ? $frontends : null,
        ]);
    }

    /**
     * editClient()
     *
     * @Route("/management/client/{id}/edit", name="management-edit-client", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editClient (Request $request, int $id)
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($id),
        ]);

        if (empty($client)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, client, error",
                "bodyClass" => "error-page",
                "message" => "No client data found",
            ]);
        }

        return $this->render('pages/management/edit-client.html.twig', [
            "pageTitle" => "Edit: " . ucwords($client->getName()),
            "pageDescription" => "Edit client page",
            "pageKeywords" => "management, client",
            "bodyClass" => "management-edit-client-page",
            "client" => $client,
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

    /**
     * viewFrontend()
     *
     * @Route("/management/frontend/{id}", name="management-view-frontend", requirements={"id"="\d+"})
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewFrontend (int $id)
    {
        return $this->render('pages/management/view-frontend.html.twig', [
            "pageTitle" => "Frontend Name",
            "pageDescription" => "frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-frontend-page"
        ]);
    }
}
