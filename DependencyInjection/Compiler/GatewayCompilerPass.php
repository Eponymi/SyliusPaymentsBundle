<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class TransportCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('acme_mailer.transport_chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'acme_mailer.transport_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'acme_mailer.transport'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addTransport',
                array(new Reference($id))
            );
        }
    }
}
