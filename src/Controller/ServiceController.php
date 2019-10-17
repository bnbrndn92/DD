<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ServiceController extends Controller
{
    /**
     * index()
     *
     * Displays the services page
     *
     * TODO - Will need access permission checks
     *
     * @Route("/services", name="services-home")
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
