<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle;

class LocaleResolver
{
    private array $locales;

    public function __construct(array $availableLocales)
    {
        $this->locales = $availableLocales;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getClosestLanguage(string $locale): ?string
    {
        $locale = str_replace('_', '-', trim($locale));
        if (false !== array_search($locale, $this->locales, true)) {
            return $locale;
        }

        if (strpos($locale, '-') > 0) {
            [$language] = explode('-', $locale, 2);
            if (false !== array_search($language, $this->locales, true)) {
                return $language;
            }
        }

        return null;
    }
}
