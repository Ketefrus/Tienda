<?php

namespace App\Controller\REST;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthRestController
{
    /**
     * @Route(
     *     "/auth/login.{_format}",
     *     requirements={"_format": "json"},
     *     defaults={"_format": "json"}
     * )
     */
    public function getTokenAction()
    {
        // The security layer will intercept this request
        return new Response('', Response:: HTTP_UNAUTHORIZED );
    }
}