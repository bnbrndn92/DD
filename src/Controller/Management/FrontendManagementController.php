<?php

namespace App\Controller\Management;

use App\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FrontendManagementController extends Controller
{
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
        return $this->render('pages/management/frontend/view.html.twig', [
            "pageTitle" => "Frontend Name",
            "pageDescription" => "frontend page",
            "pageKeywords" => "management, frontend",
            "bodyClass" => "management-frontend-page"
        ]);
    }
}