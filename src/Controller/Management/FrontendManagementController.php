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
     * assignFrontend()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/assign", name="management-assign-frontend")
     * @Route("/management/frontend/assign/{frontend}", name="management-assign-frontend-included")
     * @Route("/management/client/{clientId}/frontend/assign", name="management-assign-client-frontend", requirements={"clientId"="\d+"})
     * @Route("/management/client/{clientId}/frontend/assign/{frontend}", name="management-assign-client-frontend-included", requirements={"clientId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/assign", name="management-assign-client-service-frontend", requirements={"clientId"="\d+","serviceId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/assign/{frontend}", name="management-assign-client-service-frontend-included", requirements={"clientId"="\d+","serviceId"="\d+"})
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
                "deleted" => null,
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

        $clients = $clientRepo->findBy([
            "deleted" => null,
        ]);

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
                "deleted" => null,
            ]);
        } else if(!empty($serviceId)) {
            // Attempt to pull in services using the service ID
            $singleService = $serviceRepo->findOneBy([
                "id" => intval($serviceId),
                "disabled" => null,
            ]);

            if (!empty($singleService)) {
                // Service found load in other services using the client ID
                $services = $serviceRepo->findBy([
                    "client_id" => intval($singleService->getClientId()),
                    "deleted" => null,
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
                "deleted" => null,
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
     * viewFrontend()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}", name="management-view-frontend", requirements={"frontendId"="\d+"})
     * @Route("/management/client/{clientId}/frontend/{frontendId}", name="management-view-client-frontend", requirements={"frontendId"="\d+","clientId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/{frontendId}", name="management-view-client-service-frontend", requirements={"frontendId"="\d+", "clientId"="\d+", "serviceId"="\d+"})
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
            "deleted" => null,
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
            "deleted" => null,
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
            "deleted" => null
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
     * editFrontend()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}", name="management-edit-frontend", requirements={"frontendId"="\d+"})
     *
     * @param Request $request
     * @param int $frontendId
     *
     * @return Response
     */
    public function editService (Request $request, int $frontendId) : Response
    {
        return $this->render('pages/management/frontend/edit.html.twig', [
            "pageTitle" => "Frontend name",
            "pageDescription" => "Edit frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-edit-frontend-page",
        ]);
    }

    /**
     * deleteFrontend()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}/delete", name="management-delete-frontend", requirements={"frontendId"="\d+"})
     *
     * @param Request $request
     * @param int $frontendId
     *
     * @return Response
     */
    public function deleteClient (Request $request, int $frontendId) : Response
    {
        return $this->render('pages/management/frontend/delete.html.twig', [
            "pageTitle" => "Delete Frontend",
            "pageDescription" => "Delete frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-delete-frontend-page",
        ]);
    }
}