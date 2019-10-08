<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard-home")
     */
    public function index()
    {
        return $this->render('pages/dashboard.html.twig', [
            "pageTitle" => "Dashboard",
            "pageDescription" => "Page description",
            "pageKeywords" => "Page,Keywords",
            "bodyClass" => "dashboard-page",
        ]);
    }
}
