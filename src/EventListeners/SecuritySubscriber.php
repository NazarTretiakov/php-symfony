<?php

namespace App\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class SecuritySubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public static function getSubscribedEvents() : array
    {
        return [
            LogoutEvent::class => 'onUserLogout',
            LoginSuccessEvent::class => 'onUserLogin',
        ];
    }

    public function onUserLogin() : void
    {
        $request = $this->requestStack->getCurrentRequest();

        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'You successfully logged into account');

    }
    public function onUserLogout() : void
    {
        $request = $this->requestStack->getCurrentRequest();

        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'You successfully logged out');

    }
}