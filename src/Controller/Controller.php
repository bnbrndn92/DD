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
     *  Controller::__construct
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
     * Controller::endProcess
     *
     * Outputs a status code and response.
     * Kills the process without returning a Symfony Response
     *
     * @param int $code
     * @param bool|null $success
     * @param string|null $message
     */
    public function endProcess (int $code = 500, bool $success = null, string $message = null) : void
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