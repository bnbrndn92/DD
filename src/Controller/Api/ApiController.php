<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * @var string
     */
    private $contentType = null;

    /**
     *  ApiController::__construct
     *
     * Checks that requests aren't coming from outside of
     * The Together Agency.
     *
     * # TODO - Remove when the time comes to add a client
     * # TODO - side portion of the site.
     */
    public function __construct ()
    {
        if ((strpos($_SERVER['HTTP_HOST'], 'localhost') === false) && $_SERVER['REMOTE_ADDR'] !== "89.197.88.51") {
            $this->endProcess(403);
            die();
        }
    }

    /**
     * ApiController::checkAPIAccess
     *
     * Enforces the content type of 'application/json' and
     * checks that the APi is only being accessed by AJAX
     *
     * @param Request $request
     */
    protected function checkApiAccess (Request $request)
    {
        $this->contentType = $request->headers->get('Content-Type');

        // Check that the content type is JSON
        if ($this->contentType !== "application/json") {
            // Non JSON format
            $this->endProcess(400, false, "Invalid content-type");
        }

        // Check if an override header is present
        $override = $request->headers->get('Override');
        if (!empty($override)) {
            $override = filter_var($override, FILTER_VALIDATE_BOOLEAN);
        }

        // If no override header check that the request is from AJAX
        if (empty($override)) {
            // Check AJAX only
            if(!array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) ||empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
                // Non AJAX used
                $this->endProcess(403);
            }
        }
    }

    /**
     * ApiController::checkApiJson
     *
     * Checks the post content for JSON content.
     * Attempts to decode the JSON and if invalid the returns false.
     * Kills process if non JSON.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function checkApiJson (Request $request) : array
    {
        $data = $request->getContent();
        if (!isset($data) || empty($data)) {
            $this->endProcess(400, false, "No content");
        }

        $data = json_decode($data, true);
        if (!empty(json_last_error())) {
            $this->endProcess(400, false, "JSON decode error");
        }

        return $data;
    }

    /**
     * ApiController::endProcess
     *
     * Outputs a status code and response.
     * Kills the process without returning a Symfony Response
     *
     * @param int $code
     * @param bool|null $success
     * @param string|null $message
     */
    protected function endProcess (int $code = 500, bool $success = null, string $message = null) : void
    {
        http_response_code($code);

        $output = array();

        if ($success !== null) {
            $output['status'] = ($success) ? "success" : "failed";
        }

        if (!empty($message)) {
            $output['message'] = $message;
        }

        if (!empty($output)) {
            echo json_encode($output);
        }

        die();
    }
}