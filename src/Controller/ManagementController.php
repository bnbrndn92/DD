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
        return $this->render('pages/management.html.twig', [
            "pageTitle" => "Management Control",
            "pageDescription" => "User client & services manager",
            "pageKeywords" => "management, services, users, frontends",
            "bodyClass" => "management-page",
        ]);
    }
}
