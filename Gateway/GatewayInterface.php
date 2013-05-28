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

use Sylius\Bundle\PaymentsBundle\Model\Payment;

/**
 * The payment gateway factory creates new Gateway services, which are instances
 * of the Gateway Class.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class GatewayInterface
{   
    public function __construct(array $sources, array $availableOptions = null);
    
    /**
     * Set the availableOptions of the Gateway as keys on the options property.
     *
     * @param array $availableOptions
     */
    public function setAvailableOptions(array $availableOptions);
    
    /**
     * Get the available options of the Gateway.
     *
     * @return array
     */
    public function getAvailableOptions();
    
    /**
     * Get the options of the Gateway. Available options are keys, their values are 
     * values.
     *
     * @return array
     */
    public function getOptions();
    
    /**
     * Set the gateway options using an associative array. If a key in the options array
     * is not in the availableOptions property, an exception will be thrown.
     *
     * @param array $options
     */
    public function setOptions(array $options);
    
    /**
     * Get the sources supported by the gateway.
     *
     * @return array
     */
    public function getSources();
    
    /**
     * Set the sources supported by the gateway.
     *
     * @param array $sources
     */
    public function setSources(array $sources);
}
