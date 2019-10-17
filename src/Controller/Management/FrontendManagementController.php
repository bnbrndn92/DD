<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use App\Entity\Client;
use App\Entity\Frontend;
use App\Entity\Service;
use App\Repository\ClientRepository;
use App\Repository\FrontendRepository;
use App\Repository\ServiceRepository;
use App\Traefik\Bandwidth;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FrontendManagementController extends Controller
{
    /**
     * FrontendManagementController::assignFrontend
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/assign", name="management-frontend-assign")
     * @Route("/management/frontend/assign/{frontend}", name="management-frontend-assign-name")
     * @Route("/management/client/{clientId}/frontend/assign", name="management-client-frontend-assign", requirements={"clientId"="\d+"})
     * @Route("/management/client/{clientId}/frontend/assign/{frontend}", name="management-client-frontend-assign-name", requirements={"clientId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/assign", name="management-client-service-frontend-assign", requirements={"clientId"="\d+","serviceId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/assign/{frontend}", name="management-client-service-frontend-assign-name", requirements={"clientId"="\d+","serviceId"="\d+"})
     *
     * @param Request $request
     * @param int|null $clientId
     * @param int|null $serviceId
     * @param string|null $frontend
     *
     * @return Response
     */
    public function assignFrontend (Request $request, int $clientId = null, int $serviceId = null, string $frontend = null) : Response
    {
        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        // See if a frontend has been passed
        if (!empty($frontend)) {
            $frontend = urldecode($frontend);

            $assignedFrontend = $frontendRepo->findOneBy([
                "name" => intval($frontend),
            ]);

            if (!empty($assignedFrontend)) {
                return $this->render('pages/error.html.twig', [
                    "pageTitle" => "Error",
                    "pageDescription" => "Error page",
                    "pageKeywords" => "management, frontend, error",
                    "bodyClass" => "error-page",
                    "message" => "Frontend already assigned",
                ]);
            }
        }

        // Load in a list of clients
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $clients = $clientRepo->findAll();

        if (empty($clients)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No clients found",
            ]);
        }

        // Check if services should be loaded in
        $services = null;

        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        if (!empty($clientId)) {
            // Attempt to pull in services using the client ID
            $services = $serviceRepo->findBy([
                "client_id" => intval($clientId),
            ]);
        } else if(!empty($serviceId)) {
            // Attempt to pull in services using the service ID
            $singleService = $serviceRepo->findOneBy([
                "id" => intval($serviceId),
            ]);

            if (!empty($singleService)) {
                // Service found load in other services using the client ID
                $services = $serviceRepo->findBy([
                    "client_id" => intval($singleService->getClientId()),
                ]);
            }
        }

        // Load a list of unassigned frontends
        $frontends = $this->getDoctrine()
            ->getRepository(Bandwidth::class)
            ->findUniqueFrontends();

        // Remove assigned frontends
        foreach ($frontends as $key => $unassignedFrontend) {
            $assignedFrontend = $frontendRepo->findBy([
                "name" => $unassignedFrontend,
            ]);

            if (!empty($assignedFrontend)) {
                unset($frontends[$key]);
            }
        }

        if (empty($frontends)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No un-assigned frontends",
            ]);
        }

        return $this->render('pages/management/frontend/assign.html.twig', [
            "pageTitle" => "Assign Frontend",
            "pageDescription" => "Assign frontend",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-assign-frontend-page",
            "clients" => $clients,
            "selectedClient" => (!empty($clientId)) ? intval($clientId) : null,
            "services" => $services,
            "selectedService" => (!empty($serviceId)) ? intval($serviceId) : null,
            "frontends" => $frontends,
            "selectedFrontend" => (!empty($frontend)) ? $frontend : null,
        ]);
    }

    /**
     * FrontendManagementController::viewFrontend
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}", name="management-frontend-view", requirements={"frontendId"="\d+"})
     * @Route("/management/client/{clientId}/frontend/{frontendId}", name="management-client-frontend-view", requirements={"frontendId"="\d+","clientId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/{frontendId}", name="management-client-service-frontend-view", requirements={"frontendId"="\d+", "clientId"="\d+", "serviceId"="\d+"})
     *
     * @param int $frontendId
     * @param int $clientId
     * @param int $serviceId
     *
     * @return Response
     */
    public function viewFrontend (int $frontendId, int $clientId = null, int $serviceId = null): Response
    {
        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontend = $frontendRepo->findOneBy([
            "id" => intval($frontendId),
        ]);

        if (empty($frontend)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No assigned frontend found",
            ]);
        }

        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $service = $serviceRepo->findOneBy([
            "id" => intval($frontend->getServiceId()),
        ]);

        if (empty($service)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No service found",
            ]);
        }

        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $client = $clientRepo->findOneBy([
            "id" => intval($frontend->getClientId()),
        ]);

        if (empty($client)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No client found",
            ]);
        }

        return $this->render('pages/management/frontend/view.html.twig', [
            "pageTitle" => $frontend->getName(),
            "pageDescription" => "frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-frontend-page",
            "client" => $client,
            "service" => $service,
            "frontend" => $frontend,
        ]);
    }

    /**
     * FrontendManagementController::editFrontend
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}/edit", name="management-frontend-edit", requirements={"frontendId"="\d+"})
     *
     * @param Request $request
     * @param int $frontendId
     *
     * @return Response
     */
    public function editfrontend (Request $request, int $frontendId) : Response
    {
        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontend = $frontendRepo->findOneBy([
            "id" => intval($frontendId),
        ]);

        if (empty($frontend)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No assigned frontend found",
            ]);
        }

        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $clients = $clientRepo->findAll();

        if (empty($clients)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No clients found",
            ]);
        }

        $client = $clientRepo->findOneBy([
            "id" => $frontend->getClientId(),
        ]);

        if (empty($client)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No client found",
            ]);
        }

        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $service = $serviceRepo->findOneBy([
            "id" => $frontend->getServiceId(),
        ]);

        if (empty($service)) {
            return $this->render('pages/error.html.twig', [
                "pageTitle" => "Error",
                "pageDescription" => "Error page",
                "pageKeywords" => "management, frontend, error",
                "bodyClass" => "error-page",
                "message" => "No service found",
            ]);
        }

        return $this->render('pages/management/frontend/edit.html.twig', [
            "pageTitle" => "Frontend name",
            "pageDescription" => "Edit frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-edit-frontend-page",
            "frontend" => $frontend,
            "client" => $client,
            "clients" => $clients,
            "service" => $service,
        ]);
    }

    /**
     * FrontendManagementController::unassignFrontend
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}/unassign", name="management-frontend-unassign", requirements={"frontendId"="\d+"})
     *
     * @param Request $request
     * @param int $frontendId
     *
     * @return Response
     */
    public function unassignFrontend (Request $request, int $frontendId) : Response
    {
        return $this->render('pages/management/frontend/unassign.html.twig', [
            "pageTitle" => "Delete Frontend",
            "pageDescription" => "Delete frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-unassign-frontend-page",
        ]);
    }
}