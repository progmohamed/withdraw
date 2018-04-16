<?php

namespace LogBundle;

use LogBundle\DependencyInjection\Compiler\RelatedServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LogBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RelatedServicesPass());
    }
}
