<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\PaymentsBundle\Gateway;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * The payment gateway chain is intended to facilitate addition of new 
 * custom gateways to the Payments Bundle. By tagging your service with
 * the sylius.payment_gateway tag, it will be picked up in the gateway
 * compiler pass.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class GatewayChain
{
    private $gateways;

    public function __construct()
    {
        $this->gateways = array();
    }

    public function addGateway(GatewayInterface $gateway, $alias)
    {
        $this->gateways[$alias] = $gateway;
    }

    public function getGateway($alias)
    {
        if (array_key_exists($alias, $this->gateways)) {
           return $this->gateways[$alias];
        }
        else {
           return;
        }
    }
}
