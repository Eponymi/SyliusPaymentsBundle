<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class TransportCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sylius.gateway_chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'sylius.gateway_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'sylius.gateway'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addGateway',
                array(new Reference($id))
            );
        }
    }
}
