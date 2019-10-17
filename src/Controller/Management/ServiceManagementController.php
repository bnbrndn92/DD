<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use App\Entity\Client;
use App\Entity\Frontend;
use App\Entity\Service;
use App\Repository\ClientRepository;
use App\Repository\FrontendRepository;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceManagementController extends Controller
{
    /**
     * ServiceManagementController::viewService
     *
     * TOdO - Include auth checks
     *
     * @Route("/management/service/{serviceId}", name="management-service-view", requirements={"serviceId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}", name="management-client-service-view", requirements={"clientId"="\d+","serviceId"="\d+"})
     *
     * @param Request $request
     * @param int $serviceId
     * @param int $clientId
     *
     * @return Response
     */
    public function viewService (Request $request, int $serviceId, int $clientId = null) : Response
    {
        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $service = $serviceRepo->findOneBy([
            "id" => intval($serviceId),
        ]);

        if (empty($service)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, service, error",
                "bodyClass" => "error-page",
                "message" => "No service data found",
            ]);
        }

        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($service->getClientId()),
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

        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontends = $frontendRepo->findBy([
            "service_id" => intval($service->getId()),
        ]);

        return $this->render('pages/management/service/view.html.twig', [
            "pageTitle" => ucwords($service->getName()),
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page",
            "client" => $client,
            "service" => $service,
            "frontends" => $frontends,
        ]);
    }

    /**
     * ServiceManagementController::createService
     *
     * TODO - Include auth checks
     *
     * @Route("/management/service/create", name="management-service-create")
     * @Route("/management/client/{clientId}/service/create", name="management-client-service-create", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int|null $clientId
     *
     * @return Response
     */
    public function createService (Request $request, int $clientId = null) : Response
    {
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        // Check if a clientId has been passed
        $client = null;
        if (!empty($clientId)) {
            $client = $clientRepo->findOneBy([
                "id" => intval($clientId),
            ]);

            if (empty($client)) {
                $client = null;
            }
        }

        $allClients = $clientRepo->findAll();

        return $this->render('pages/management/service/create.html.twig', [
            "pageTitle" => "Service Name",
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page",
            "client" => $client,
            "allClients" => $allClients,
        ]);
    }

    /**
     * ServiceManagementController::editService
     *
     * TODO - Include auth checks
     *
     * @Route("/management/service/{serviceId}/edit", name="management-service-edit", requirements={"serviceId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/edit", name="management-client-service-edit", requirements={"serviceId"="\d+","clientId"="\d+"})
     *
     * @param Request $request
     * @param int $serviceId
     * @param int $clientId
     *
     * @return Response
     */
    public function editService (Request $request, int $serviceId, int $clientId = null) : Response
    {
        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $service = $serviceRepo->findOneBy([
            "id" => intval($serviceId),
        ]);

        if (empty($service)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, service, error",
                "bodyClass" => "error-page",
                "message" => "No service data found",
            ]);
        }

        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($service->getClientId()),
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

        return $this->render('pages/management/service/edit.html.twig', [
            "pageTitle" => "Edit: " . ucwords($service->getName()),
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page",
            "client" => $client,
            "service" => $service,
        ]);
    }

    /**
     * ServiceManagementController::deleteService
     *
     * TODO - Include auth checks
     *
     * @Route("/management/service/{serviceId}/delete", name="management-service-delete", requirements={"serviceId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/delete", name="management-client-service-delete", requirements={"serviceId"="\d+", "clientId"="\d+"})
     *
     * @param Request $request
     * @param int $serviceId
     * @param int $clientId
     *
     * @return Response
     */
    public function deleteService (Request $request, int $serviceId, int $clientId = null) : Response
    {
        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $service = $serviceRepo->findOneBy([
            "id" => intval($serviceId),
        ]);

        if (empty($service)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, service, error",
                "bodyClass" => "error-page",
                "message" => "No service data found",
            ]);
        }

        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($service->getClientId()),
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

        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontends = $frontendRepo->findBy([
            "service_id" => intval($service->getId()),
        ]);


        return $this->render('pages/management/service/delete.html.twig', [
            "pageTitle" => "Delete: " . ucwords($service->getName()),
            "pageDescription" => "Delete service",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-delete-service-page",
            "client" => $client,
            "service" => $service,
            "frontends" => $frontends,
        ]);
    }
}