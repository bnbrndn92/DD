<?php

namespace App\Controller;

use App\Entity\Frontend;
use App\Repository\FrontendRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FrontendController extends Controller
{
    /**
     * getCurrentFrontendsByService()
     *
     * Attempts to get all current active frontends by the client id
     *
     * TODO - Will need access permission checks
     *
     * @Route("/serviceId/{serviceId}/frontends/current/", name="service-frontends-current", requirements={"serviceId"="\d+"})
     * @Route("/client/{clientId}/service/{serviceId}/frontends/current/", name="client-service-frontends-current", requirements={"clientId"="\d+", "serviceId"="\d+"})
     *
     * @param Request $request
     * @param int $serviceId
     * @param int $clientId
     *
     * @return Response
     */
    public function getCurrentFrontendsByService (Request $request, int $serviceId, int $clientId = null) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $frontends = $frontendRepo->findBy([
            "service_id" => intval($serviceId),
        ]);

        if (empty($frontends)) {
            return new JsonResponse([
                "success" => true,
                "message" => "No frontends available",
                "data" => array()
            ],204);
        }

        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($frontends, 'json');

        return new JsonResponse([
            "success" => true,
            "message" => "Frontends found",
            "data" => json_decode($data),
        ],200);
    }

    /**
     * assignFrontend()
     *
     * TODO - Add authorization
     *
     * Process:
     * Checks API access & JSON body content is present
     *
     * @Route("/frontend/assign", name="frontend-assign")
     *
     * @param Request $request
     * @return Response
     */
    public function assignFrontend (Request $request) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

        // Get the entity manager and create an instance
        $entityManager = $this->getDoctrine()->getManager();
        $frontend = new Frontend();

        if (!$frontend->factory($data)) {
            $this->abruptEnd(400, "Frontend factory failed");
        }

        // Check to see if the frontend already exists by the safe name
        /** @var FrontendRepository $frontendRepo */
        $frontendRepo = $this->getDoctrine()
            ->getRepository(Frontend::class);

        $existingService = $frontendRepo->findByFrontendName($frontend->getName());

        if (!empty($existingService)) {
            // Frontend with that name already exists -> return response
            return new JsonResponse([
                "success" => false,
                "message" => "A frontend with that name already exists.",
            ],409);
        }

        $inserted = false;
        try {
            // Persist & flush the data
            $entityManager->persist($frontend);
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
            "message" => "Frontend created.",
            "location" => $this->generateUrl("management-view-frontend", ["frontendId" => $frontend->getId()])
        ],201);
    }

    /**
     * editFrontend()
     *
     * Process:
     *
     * @Route("/frontend/edit", name="frontend-edit")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editFrontend (Request $request) : Response
    {
        return new JsonResponse([
            "success" => false,
            "message" => "Method incomplete"
        ],500);
    }
}
