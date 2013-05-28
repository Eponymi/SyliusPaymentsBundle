<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\PaymentsBundle\Bridge;

use Sylius\Bundle\PaymentsBundle\Model\Payment;
use Sylius\Bundle\PaymentsBundle\Model\Gateway;

/**
 * Bridged Payment Gateway.
 *
 * This class facilitates calling the methods defined by PaymentProcessorInterface using
 * a gateway.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class BridgedGateway extends Gateway
{   
	private $bridged;
	
    /**
     * Payment Sources
     *
     * The sources which this gateway accepts, e.g. CreditCard, BitCoin, etc.
     * Available sources are defined in \Sylius\Bundle\PaymentsBundle\Model\Sources.
     * Forms for accepted sources will be displayed when this gateway is selected.
     *
     * @var array
     */
    private $sources;
    
    /**
     * Configuration options for the gateway. These are used in the backend display of
     * Payment Gateway. So when your user wants to add a payment gateway, all they need
     * to do is configure the appropriate options in the backend GUI. 
     *
     * @var array
     */
    private $options;
    
    /**
     * Constructor.
     *
     * @param class $bridged
     * @param array $sources
     * @param array $methods
     * @param array $availableOptions
     */
    public function __construct($bridged, array $sources, array $availableOptions = null)
    {
    	$this->bridged = $bridged;
    	$this->setAvailableOptions($availableOptions);
    }
    
    /**
     * {@inheritdoc}
     */
    public function processing(Payment $payment)
    {
    	call_user_func(array($this->bridged, $this->methods['process']));
    }
    
    /**
     * {@inheritdoc}
     */
    public function pending(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    public function completed(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    public function void(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    public function failed(Payment $payment);
}
