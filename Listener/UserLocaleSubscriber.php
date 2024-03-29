<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle\Listener;

use Arthem\Bundle\LocaleBundle\LocaleResolver;
use Arthem\Bundle\LocaleBundle\Model\UserLocaleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserLocaleSubscriber implements EventSubscriberInterface
{
    private SessionInterface $session;
    private LocaleResolver $localeResolver;

    public function __construct(SessionInterface $session, LocaleResolver $localeResolver)
    {
        $this->session = $session;
        $this->localeResolver = $localeResolver;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof UserLocaleInterface) {
            return;
        }

        if (null !== $user->getLocale() && $user->getLocale()) {
            $this->session->set('_locale', $this->localeResolver->getClosestLanguage($user->getLocale()));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }
}
