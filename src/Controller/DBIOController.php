<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class DBIOController extends Controller
{
    /**
     * @Route("/dbio", name="dbio-home")
     */
    public function index()
    {
        return $this->render('pages/dbio.html.twig', [
            "pageTitle" => "DBIO",
            "pageDescription" => "Page description",
            "pageKeywords" => "Page,Keywords",
            "bodyClass" => "dashboard-page",
        ]);
    }
}
