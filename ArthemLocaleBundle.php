<?php

namespace Arthem\Bundle\LocaleBundle;

use Arthem\Bundle\LocaleBundle\DependencyInjection\Compiler\EventMessageConsumerHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ArthemLocaleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    }
}
