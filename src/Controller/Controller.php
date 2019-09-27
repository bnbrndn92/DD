<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class Controller extends AbstractController
{
    /**
     * @var string
     */
    private $contentType = null;

    /**
     *  __construct()
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
            $this->abruptEnd(403);
            die();
        }
    }

    /**
     * checkAPIAccess()
     *
     * @param Request $request
     */
    protected function checkApiAccess (Request $request)
    {
        $this->contentType = $request->headers->get('Content-Type');

        // Check that the content type is JSON
        if ($this->contentType !== "application/json") {
            // Non JSON format
            $this->abruptEnd(400);
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
                $this->abruptEnd(403);
            }
        }
    }

    /**
     * abruptEnd()
     *
     * Abruptly ends the process.
     *
     * @param int $code
     * @param string|null $message
     */
    public function abruptEnd (int $code = 500, string $message = null) : void
    {
        http_response_code($code);

        if (!empty($message)) {
            if (!empty($this->contentType) && ($this->contentType === "application/json")) {
                echo json_encode(["message" => $message]);
            } else {
                echo $message;
            }
        }

        die();
    }

    /**
     * checkApiJson()
     *
     * Checks the post content for JSON content.
     * Attempts to decode the JSON and if invalid the returns false.
     * Kills process if non JSON.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function checkApiJson (Request $request)
    {
        $data = $request->getContent();
        if (!isset($data) || empty($data)) {
            $this->abruptEnd(400);
        }

        $data = json_decode($data, true);
        if (!empty(json_last_error())) {
            $this->abruptEnd(400);
        }

        return $data;
    }
}