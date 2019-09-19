<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends Controller
{
    /**
     * @Route("/services", name="services")
     */
    public function index()
    {
        return $this->render('pages/services.html.twig', [
            "pageTitle" => "Services",
            "pageDescription" => "Page description",
            "pageKeywords" => "Page,Keywords",
            "bodyClass" => "dashboard-page",
        ]);
    }
}
