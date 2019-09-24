<?php

declare(strict_types=1);

namespace Arthem\Bundle\LocaleBundle\Model;

interface UserLocaleInterface
{
    public function getLocale(): ?string;
}
