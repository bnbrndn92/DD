<?php

namespace App\Controller\Api;

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

class ServiceApiController extends ApiController
{
    /**
     * ServiceApiController::getServicesByClient
     *
     * Process:
     * Checks API access
     * Attempts to get all current active services by the client id
     * Returns an empty data array if no services are found
     *
     * TODO - Will need access permission checks
     *
     * @Route("/api/client/{clientId}/services", name="api-client-services", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int $clientId
     *
     * @return Response
     */
    public function getServicesByClient (Request $request, int $clientId) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        /** @var ServiceRepository $serviceRepo */
        $serviceRepo = $this->getDoctrine()
            ->getRepository(Service::class);

        $services = $serviceRepo->findBy([
            "client_id" => intval($clientId),
        ]);

        if (empty($services)) {
            return new JsonResponse([
                "success" => true,
                "message" => "No services available",
                "data" => array()
            ],204);
        }

        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($services, 'json');

        return new JsonResponse([
            "success" => true,
            "message" => "Services found",
            "data" => json_decode($data),
        ],200);
    }

    /**
     * ServiceApiController::createService
     *
     * Process:
     * Checks API access & JSON body content is present
     * Creates a new Service entity
     * Checks that the safe name doesn't already exist
     * Creates the new Service
     *
     * TODO - Will need access permission checks
     *
     * @Route("/api/service/create", name="api-service-create")
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
            $this->endProcess(400, false, "Service factory failed");
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
            "location" => $this->generateUrl("management-service-view", [
                "serviceId" => $service->getId(),
            ])
        ],201);
    }

    /**
     * ServiceApiController::editService
     *
     * Process:
     * Checks API access & JSON body content is present
     *
     * TODO - Will need access permission checks
     *
     * @Route("/api/service/{serviceId}/edit", name="api-service-edit", requirements={"serviceId"="\d+"})
     *
     * @param Request $request
     * @param int $serviceId
     *
     * @return Response
     */
    public function editService (Request $request, int $serviceId) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

        $entityManager = $this->getDoctrine()->getManager();
        $service = $entityManager->getRepository(Service::class)
            ->findOneBy([
                "id" => intval($serviceId),
        ]);

        if (empty($service)) {
            return new JsonResponse([
                "success" => false,
                "message" => "No service found",
            ],404);
        }

        // Check for updated fields
        if (array_key_exists("service_name", $data)) {
            // Compare the old and the new
            if (trim($data['service_name']) !== $service->getName()) {
                // Update the service name
                $service->setName(trim($data['service_name']));
                $service->generateSafeName($service->getName());

                // Check if a service exists with that name already
                $existingService = $entityManager->getRepository(Service::class)
                    ->findByServiceSafeName($service->getSafeName(), true);

                if (!empty($existingService)) {
                    // Service with that name already exists
                    return new JsonResponse([
                        "success" => false,
                        "message" => "A service with that name already exists.",
                    ],409);
                }
            }
        }

        $entityManager->flush();

        return new JsonResponse([
            "success" => true,
            "message" => "Service updated",
            "location" => $this->generateUrl("management-service-view", [
                "serviceId" => $service->getId(),
            ])
        ],200);
    }

    /**
     * ServiceApiController::deleteService
     *
     * Process:
     * TODO - Implement deletion process
     *
     * TODO - Will need access permission checks
     *
     * @Route("/api/service/{serviceId}/delete", name="api-service-delete", requirements={"serviceId"="\d+"})
     * @Route("/api/client/{clientId}/service/{serviceId}/delete", name="api-client-service-delete", requirements={"serviceId"="\d+","clientId"="\d+"})
     *
     * @param Request $request
     * @param int $serviceId
     * @param int $clientId
     *
     * @return Response
     */
    public function deleteService (Request $request, int $serviceId, int $clientId = null) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

//        $entityManager = $this->getDoctrine()->getManager();
//        $service = $entityManager->getRepository(Service::class)->findOneBy(["id" => intval($serviceId)]);
//
//        if (empty($service)) {
//            return new JsonResponse([
//                "success" => false,
//                "message" => "No service found",
//            ],404);
//        }
//
//        $client = $entityManager->getRepository(Client::class)->findOneBy(["id" => intval($service->getClientId())]);
//
//        if (empty($client)) {
//            return new JsonResponse([
//                "success" => false,
//                "message" => "No associated client found",
//            ],500);
//        }
//
//        # TODO - Implement deletion

        return new JsonResponse([
            "success" => false,
            "message" => "Method not complete",
        ],500);
    }
}