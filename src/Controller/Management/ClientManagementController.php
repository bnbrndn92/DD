<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use App\Entity\Client;
use App\Entity\Frontend;
use App\Entity\Service;
use App\Repository\ClientRepository;
use App\Repository\FrontendRepository;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ClientManagementController extends Controller
{
    /**
     * viewClient()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/client/{clientId}", name="management-view-client", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int $clientId
     *
     * @return Response
     */
    public function viewClient (Request $request, int $clientId) : Response
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($clientId),
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

        return $this->render('pages/management/client/view.html.twig', [
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
     * createClient()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/client/create", name="management-create-client")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createClient (Request $request) : Response
    {
        return $this->render('pages/management/client/create.html.twig', [
            "pageTitle" => "Create Client",
            "pageDescription" => "Management create client page",
            "pageKeywords" => "management, create, client",
            "bodyClass" => "management-create-client-page",
        ]);
    }

    /**
     * editClient()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/client/{clientId}/edit", name="management-edit-client", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int $clientId
     *
     * @return Response
     */
    public function editClient (Request $request, int $clientId) : Response
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($clientId),
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

        return $this->render('pages/management/client/edit.html.twig', [
            "pageTitle" => "Edit: " . ucwords($client->getName()),
            "pageDescription" => "Edit client page",
            "pageKeywords" => "management, client",
            "bodyClass" => "management-edit-client-page",
            "client" => $client,
        ]);
    }

    /**
     * deleteClient()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/client/{clientId}/delete", name="management-delete-client", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int $clientId
     *
     * @return Response
     */
    public function deleteClient (Request $request, int $clientId) : Response
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($clientId),
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

        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $services = $serviceRepo->findBy([
            "client_id" => intval($clientId),
        ]);

        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontends = $frontendRepo->findBy([
            "client_id" => intval($clientId),
        ]);

        return $this->render('pages/management/client/delete.html.twig', [
            "pageTitle" => "Delete: " . ucwords($client->getName()),
            "pageDescription" => "Delete client page",
            "pageKeywords" => "management, client",
            "bodyClass" => "management-delete-client-page",
            "client" => $client,
            "services" => $services,
            "frontends" => $frontends,
        ]);
    }
}