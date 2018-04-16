<?php

namespace LocaleBundle;

use LocaleBundle\DependencyInjection\Compiler\RelatedServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LocaleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RelatedServicesPass());
    }
}
