<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle\Listener;

use Arthem\Bundle\LocaleBundle\LocaleResolver;
use Arthem\Bundle\LocaleBundle\Model\UserLocaleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserLocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly LocaleResolver $localeResolver,
    ) {
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof UserLocaleInterface) {
            return;
        }

        if (null !== $user->getLocale() && $user->getLocale()) {
            try {
                $this->requestStack->getSession()->set('_locale', $this->localeResolver->getClosestLanguage($user->getLocale()));
            } catch (SessionNotFoundException $e) {
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }
}
