<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    /**
     *  __construct()
     *
     * Checks that requests aren't coming from outside of
     * The Together Agency.
     *
     * # TODO - Remove when the time comes to add a client
     * # TODO - side portion of the site.
     *
     */
    public function __construct ()
    {
        if ((strpos($_SERVER['HTTP_HOST'], 'localhost') === false) && $_SERVER['REMOTE_ADDR'] !== "89.197.88.51") {
            http_response_code(403);
            die();
        }
    }
}