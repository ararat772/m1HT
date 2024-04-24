<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ApiOptionsSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof MethodNotAllowedHttpException) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->isMethod('OPTIONS')) {
            return;
        }

        $allowedMethods = 'OPTIONS, ' . $exception->getHeaders()['Allow'];

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Methods', $allowedMethods);

        $event->setResponse($response);
        $event->allowCustomResponseCode();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
