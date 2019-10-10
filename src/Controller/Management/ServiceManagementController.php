<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceManagementController extends Controller
{
    /**
     * viewService()
     *
     * TOdO - Include auth checks
     *
     * @Route("/management/service/{id}", name="management-view-service", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function viewService (Request $request, int $id) : Response
    {
        return $this->render('pages/management/service/view.html.twig', [
            "pageTitle" => "Service Name",
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page"
        ]);
    }

    /**
     * createService()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/service/create", name="management-create-service")
     * @Route("/management/client/{clientId}/service/create", name="management-create-client-service", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int|null $clientId
     *
     * @return Response
     */
    public function createService (Request $request, int $clientId = null) : Response
    {
        return $this->render('pages/management/service/create.html.twig', [
            "pageTitle" => "Service Name",
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page"
        ]);
    }

    /**
     * editService()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/service/{id}", name="management-edit-service", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function editService (Request $request, int $id) : Response
    {
        return $this->render('pages/management/service/edit.html.twig', [
            "pageTitle" => "Service Name",
            "pageDescription" => "Service page",
            "pageKeywords" => "management, service",
            "bodyClass" => "management-service-page"
        ]);
    }

    /**
     * deleteService()
     *
     * TODO - Include auth checks
     *
     * @Route("/management/service/{id}/delete", name="management-delete-service", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function deleteClient (Request $request, int $id) : Response
    {
        return $this->render('pages/management/service/delete.html.twig', [
            "pageTitle" => "Edit: ",
            "pageDescription" => "Edit client page",
            "pageKeywords" => "management, client",
            "bodyClass" => "management-edit-client-page",
        ]);
    }
}