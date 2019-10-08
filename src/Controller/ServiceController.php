<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends Controller
{
    /**
     * index()
     *
     * Displays the services page
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

    /**
     * createService()
     *
     * Process:
     * Checks API access & JSON body content is present
     * Creates a new Service entity
     * Checks that the safe name doesn't already exist
     * Creates the new Service
     *
     * @Route("/service/create", name="service-create")
     *
     * @param Request $request
     * @return Response
     */
    public function createService (Request $request): Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

        // Get the entity manager and create an instance
        $entityManager = $this->getDoctrine()->getManager();
        $service = new Service();

        if (!$service->factory($data)) {
            $this->abruptEnd(400, "Service factory failed");
        }

        // Check to see if the service already exists by the safe name
        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $existingService = $serviceRepo->findByServiceSafeName($service->getSafeName());

        if (!empty($existingService)) {
            // Service with tha safe name already exists -> return response
            return new JsonResponse([
                "success" => false,
                "message" => "A service with that name already exists.",
            ],409);
        }

        $inserted = false;
        try {
            // Persist & flush the data
            $entityManager->persist($service);
            $entityManager->flush();
            $inserted = true;
        } catch (\Exception $e) {
            return new JsonResponse([
                "success" => false,
                "message" => "A DB exception occurred",
            ],500);
        }

        if (!$inserted) {
            return new JsonResponse([
                "success" => false,
                "message" => "Creation failed."
            ],500);
        }

        return new JsonResponse([
            "success" => true,
            "message" => "Service created.",
        ],201);
    }
}
