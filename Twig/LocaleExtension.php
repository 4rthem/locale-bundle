<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle\Twig;

use Locale;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class LocaleExtension extends Twig_Extension
{
    private UrlGeneratorInterface $router;
    private array $locales;
    private static ?array $localeNames = null;

    public function __construct(UrlGeneratorInterface $router, array $availableLocales)
    {
        $this->router = $router;
        $this->locales = $availableLocales;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('path_for_locale', [$this, 'getPathForLocale']),
            new Twig_SimpleFunction('get_available_locales', [$this, 'getAvailableLocales']),
        ];
    }

    public function getPathForLocale(Request $request, string $locale, bool $url = false)
    {
        $params = array_merge($request->attributes->all(), $request->query->all());
        $params = array_filter($params, function (string $key) {
            return 0 !== strpos($key, '_');
        }, ARRAY_FILTER_USE_KEY);
        $params['_locale'] = $locale;

        if (!$request->attributes->has('_route')) {
            return null;
        }

        return $this
            ->router
            ->generate(
                $request->attributes->get('_route'),
                $params,
                $url ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH
            );
    }

    public function getAvailableLocales(): array
    {
        if (null !== self::$localeNames) {
            return self::$localeNames;
        }

        self::$localeNames = [];
        foreach ($this->locales as $l) {
            self::$localeNames[str_replace('_', '-', $l)] = ucfirst(Locale::getDisplayName($l, $l));
        }

        return self::$localeNames;
    }
}
