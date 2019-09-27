<?php

namespace App\Controller;

use App\Entity\Client;
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
     *
     *
     * @Route("/client/create", name="client-create")
     *
     * @param Request $request
     * @return Response
     */
    public function createClient (Request $request): Response
    {
        // Check that API access is allowed-
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
            "success" => false,
            "message" => "Client created.",
        ],201);
    }
}