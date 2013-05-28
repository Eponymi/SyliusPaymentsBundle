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

/**
 * The payment gateway factory creates new Gateway services, which are instances
 * of the Gateway Class.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class GatewayFactory
{
    public function manufacture(array $methods)
    {
        $gateway = new Gateway($methods);

        // $gateway->methods = $methods;

        return $gateway;
    }
}
