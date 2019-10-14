<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FrontendManagementController extends Controller
{
    /**
     * viewFrontend()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/{frontendId}", name="management-view-frontend", requirements={"frontendId"="\d+"})
     *
     * @param int $frontendId
     *
     * @return Response
     */
    public function viewFrontend (int $frontendId): Response
    {
        return $this->render('pages/management/frontend/view.html.twig', [
            "pageTitle" => "Frontend Name",
            "pageDescription" => "frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-frontend-page",
        ]);
    }

    /**
     * assignFrontend()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/frontend/assign", name="management-assign-frontend")
     * @Route("/management/client/{clientId}/frontend/assign", name="management-assign-client-frontend", requirements={"clientId"="\d+"})
     * @Route("/management/client/{clientId}/service/{serviceId}/frontend/assign", name="management-assign-client-service-frontend", requirements={"clientId"="\d+","serviceId"="\d+"})
     *
     * @param Request $request
     * @param int|null $clientId
     * @param int|null $serviceId
     *
     * @return Response
     */
    public function assignFrontend (Request $request, int $clientId = null, int $serviceId = null) : Response
    {
        return $this->render('pages/management/frontend/assign.html.twig', [
            "pageTitle" => "Assign Frontend",
            "pageDescription" => "Assign frontend",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-assign-frontend-page",
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