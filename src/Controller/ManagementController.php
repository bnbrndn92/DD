<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class ManagementController extends Controller
{
    /**
     * index()
     *
     * @Route("/management", name="management-home")
     */
    public function index()
    {
        return $this->render('pages/bandwidth.html.twig', [
            "pageTitle" => "Bandwidth",
            "pageDescription" => "Page description",
            "pageKeywords" => "Page,Keywords",
            "bodyClass" => "bandwidth-page",
        ]);
    }
}
