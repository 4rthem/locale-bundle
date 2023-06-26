<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle\Listener;

use Arthem\Bundle\LocaleBundle\LocaleResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LocaleResolver $localeResolver)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($locale = $request->attributes->get('_locale')) {
            $closest = $this->localeResolver->getClosestLanguage($locale);
            if ($closest !== $locale) {
                $request->attributes->set('_locale', $closest);
                $request->setLocale($closest);
            }
        }

        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        $session = $request->getSession();

        if ($locale = $request->attributes->get('_locale')) {
            $session->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $default = $request->getPreferredLanguage($this->localeResolver->getLocales());
            $locale = $session->get('_locale', $default);
            $request->setLocale($locale);
            $request->attributes->set('_locale', $locale);
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($locale = $event->getRequest()->getLocale()) {
            $event->getResponse()->headers->set('Content-Language', $locale);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 17]],
            KernelEvents::RESPONSE => [['onKernelResponse', 15]],
        ];
    }
}
