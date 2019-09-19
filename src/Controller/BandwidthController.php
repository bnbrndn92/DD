<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class BandwidthController extends Controller
{
    /**
     * @Route("/bandwidth", name="bandwidth")
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
