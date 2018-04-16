<?php

namespace AdminBundle;

use AdminBundle\DependencyInjection\Compiler\AddExpressionLanguageProvidersPass;
use AdminBundle\DependencyInjection\Compiler\BackendMenuFixturesPass;
use AdminBundle\DependencyInjection\Compiler\RelatedServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AddExpressionLanguageProvidersPass());
        $container->addCompilerPass(new BackendMenuFixturesPass());
        $container->addCompilerPass(new RelatedServicesPass());
    }
}
