<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureListener implements EventSubscriberInterface
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $exception = $event->getAuthenticationException();

        $response = new Response('Erreur d\'authentification. Vérifiez vos identifiants.', 401);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationFailureEvent::class => 'onAuthenticationFailure',
        ];
    }
}
