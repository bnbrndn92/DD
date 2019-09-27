<?php

namespace App\Controller;

use App\Entity\Frontend;
use App\Repository\FrontendRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends Controller
{
    /**
     * createFrontend()
     *
     * Process:
     * Checks API access & JSON body content is present
     *
     * @Route("/frontend/create", name="frontend-create")
     *
     * @param Request $request
     * @return Response
     */
    public function createFrontend (Request $request) : Response
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
        ],201);
    }
}
