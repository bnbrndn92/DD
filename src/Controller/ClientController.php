<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Frontend;
use App\Entity\Service;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends Controller
{
    /**
     * createClient()
     *
     * Process:
     * Checks API access & JSON body content is present
     * Creates a new Client entity
     * Checks that a Client with the generated safe name doesn't exist
     * Creates the Client
     *
     * @Route("/client/create", name="client-create")
     *
     * @param Request $request
     * @return Response
     */
    public function createClient (Request $request): Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

        // Get the entity manager and create an instance
        $entityManager = $this->getDoctrine()->getManager();
        $client = new Client();

        if (!$client->factory($data)) {
            $this->abruptEnd(400, "Client factory failed");
        }

        // Check to see if the client already exists by the safe name
        /** @var ClientRepository $clientRepo */
        $clientRepo = $this->getDoctrine()
            ->getRepository(Client::class);

        $existingClient = $clientRepo->findByClientSafeName($client->getSafeName());

        if (!empty($existingClient)) {
            // Client with tha safe name already exists -> return response
            return new JsonResponse([
                "success" => false,
                "message" => "A client with that name already exists.",
            ],409);
        }

        $inserted = false;
        try {
            // Persist & flush the data
            $entityManager->persist($client);
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
            "message" => "Client created.",
            "location" => "/management/client/" . $client->getId()
        ],201);
    }

    /**
     * editClient()
     *
     * Process:
     * Checks API access & JSON body content is present
     * Loads the required client via the repository
     *      Compares the name for any changes
     *          Updates the name and generates a safe name
     *          Checks the safe name doesn't exist
     * Flushes the update
     *
     * @Route("/client/{clientId}/edit", name="client-edit", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int $clientId
     *
     * @return Response
     */
    public function editClient (Request $request, int $clientId) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->findOneBy(["id" => intval($clientId)]);

        if (empty($client)) {
            return new JsonResponse([
                "success" => false,
                "message" => "No client found",
            ],404);
        }

        // Check for updated fields
        if (array_key_exists("client_name", $data)) {
            // Compare the old and the new
            if (trim($data['client_name']) !== $client->getName()) {
                // Update the client name
                $client->setName(trim($data['client_name']));
                $client->generateSafeName($client->getName());

                // Check if a client exists already with this safe name
                $existingClient = $entityManager->getRepository(Client::class)->findByClientSafeName($client->getSafeName());

                if (!empty($existingClient)) {
                    // Client with tha safe name already exists -> return response
                    return new JsonResponse([
                        "success" => false,
                        "message" => "A client with that name already exists.",
                    ],409);
                }
            }
        }

        $entityManager->flush();

        return new JsonResponse([
            "success" => true,
            "message" => "Client updated",
            "location" => "/management/client/" . $client->getId()
        ],200);
    }

    /**
     * deleteClient()
     *
     * Process:
     * TODO
     *
     * @Route("/client/{clientId}/delete", name="client-delete", requirements={"clientId"="\d+"})
     *
     * @param Request $request
     * @param int $clientId
     *
     * @return Response
     */
    public function deleteClient (Request $request, int $clientId) : Response
    {
        // Check that API access is allowed
        $this->checkApiAccess($request);

        // Check that POST data has been provided & decode
        $data = $this->checkApiJson($request);

        $entityManager = $this->getDoctrine()->getManager();
        $client = $entityManager->getRepository(Client::class)->findOneBy(["id" => intval($clientId)]);

        if (empty($client)) {
            return new JsonResponse([
                "success" => false,
                "message" => "No client found",
            ],404);
        }

        $services = $entityManager->getRepository(Service::class)->findBy(["client_id" => intval($clientId)]);
        $frontends = $entityManager->getRepository(Frontend::class)->findBy(["client_id" => intval($clientId)]);

        if (intval($clientId) !== intval($data['client_delete'])) {
            return new JsonResponse([
                "success" => false,
                "message" => "Invalid request",
            ],400);
        }

        # TODO - Implement deletion

//        // Remove any frontends
//        if (!empty($frontends)) {
//            foreach ($frontends as $frontend) {
//                /** @var Frontend $frontend */
//                $frontend->setDeleted();
//                var_dump($frontend);
//            }
//        }
//
////        // Remove any services
////        if (!empty($services)) {
////            foreach ($services as $service) {
////                $service->setDeleted();
////            }
////        }

        return new JsonResponse([
            "success" => false,
            "message" => "Method not complete",
        ],500);
    }
}