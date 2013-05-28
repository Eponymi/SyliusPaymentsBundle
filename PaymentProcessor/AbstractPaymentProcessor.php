<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\PaymentsBundle\PaymentProcessor;

use Sylius\Bundle\PaymentsBundle\Model\Payment;
use Sylius\Bundle\PaymentsBundle\Gateway\GatewayInterface;

/**
 * Abstract Payment Processor.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class AbstractPaymentProcessor implements PaymentProcessorInterface
{   
	private $gateway;

	/**
     * Constructor
     */
    public function __construct(GatewayInterface $gateway = null)
    {
    	$this->gateway = $gateway;
    }

    /**
     * {@inheritdoc}
     */
    public function run($method, Payment $payment)
    {
    	$newState = 'STATE_' . strtoupper($method);
    	
    	if($this->gateway instanceof BridgedGateway) {
    		$result = $this->gateway->processing($payment);
    	} else {
    		$result = $this->$method($payment);
    	}
    	
    	if($result === true) {
    		$payment->setState(PaymentStates::$newState);
    		return $payment;
    	}
    	
    	throw new \Exception(sprintf('Failed to change payment state from "%s" to "%s"', $payment->getState(), $newState));
    }
    
    /**
     * {@inheritdoc}
     */
    abstract public function processing(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    abstract public function pending(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    abstract public function completed(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    abstract public function void(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    abstract public function failed(Payment $payment);
}
