<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle\Listener;

use Arthem\Bundle\LocaleBundle\LocaleResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var LocaleResolver
     */
    private $localeResolver;

    public function __construct(SessionInterface $session, LocaleResolver $localeResolver)
    {
        $this->session = $session;
        $this->localeResolver = $localeResolver;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $securityUser = $event->getAuthenticationToken()->getUser();
        $user = $securityUser->getUser();
        if (!$user instanceof UserInterface) {
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
