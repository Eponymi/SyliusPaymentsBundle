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
 * Payment Gateway.
 *
 * This class is more like a Gateway wrapper. It facilitates calling the methods defined 
 * by GatewayInterface and provides a class for the GatewayFactory to create with all of 
 * the required methods for passing through payment states.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class Gateway implements GatewayInterface
{   
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
     * The references to methods of the bridged class which will be called in the
     * appropriate situation. The keys are methods of GatewayInterface. The values
     * should be the names of the methods of $bridged which accomplish a payment state
     * change. If left false, a method will simply change the state of the Payment and
     * return true.
     *
     * @var array
     */
    private $methods = array(
    	'checkout' => array(
    		'callable' => false,
    		'parameters' => null
    	),
    	'processPayment' => array(
    		'callable' => false,
    		'parameters' => null
    	),
    	'pendPayment' => false,
    	'completePayment' => 'purchase',
    	'failPayment' => false,
    	'voidPayment' => 'void'
    );
    
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
    public function __construct($bridged, array $sources, array $methods, array $availableOptions = null)
    {
    	$this->bridged = get_class($bridged);
    	$this->setMethods($methods);
    	$this->setAvailableOptions($availableOptions);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMethods(array $methods)
    {
    	foreach($methods as $nativeMethod => $bridgedMethod) {
    		if(!array_key_exists($method, $this->methods)) {
    			throw new \Exception("Tried to set undefined Payment Gateway method: " . $method);
    		}
    		$this->methods[$nativeMethod] = $bridgedMethod;
    	}
    	
    	return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMethods()
    {
    	return $this->methods;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMethod($method, $callable)
    {
    	if(!array_key_exists($method, $this->methods)) {
    		throw new \Exception("Tried to set undefined Payment Gateway method: " . $method);
    	}
    	
    	$this->methods[$method] = $callable;
    	
    	return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMethod($method)
    {
    	if(!array_key_exists($method, $this->methods)) {
    		throw new \Exception("Tried to get an undefined payment gateway method: " . $method);
    	}
    	
    	return $this->methods[$method];
    }
    
    /**
     * {@inheritdoc}
     */
    public function setAvailableOptions(array $availableOptions)
    {
    	$this->options->array_keys = $availableOptions;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAvailableOptions()
    {
    	return array_keys($this->options);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
    	return $this->options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
    	foreach($options as $option => $value) {
    		if(!array_key_exists($option, $this->availableOptions)) {
    			throw new \Exception("Option type " . $option . " is not configured.");
    		}
    		$this->options[$option] = $value;
    	}
    	
    	return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function run($method, Payment $payment)
    {
    	if($this->methods[$method] == false) {
    		return $this->$method($payment);
    	} else {
    	    // method resolver; see controller resolver in book
    		return call_user_func(array($this->bridged, $this->methods[$method]), $payment->getSource());
    	}
    }
    
    /**
     * {@inheritdoc}
     */
    public function processPayment(Payment $payment)
    {
    	call_user_func(array($this->bridged, $this->methods['process']));
    }
    
    /**
     * {@inheritdoc}
     */
    public function pendPayment(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    public function completePayment(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    public function voidPayment(Payment $payment);
    
    /**
     * {@inheritdoc}
     */
    public function failPayment(Payment $payment);
}
