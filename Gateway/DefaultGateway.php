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
     * Methods used for changing payment state.
     *
     * The references to methods which will be called to assign the state matching the
     * given key to the payment.
     * The top-level keys are methods of GatewayInterface. The second-level key "callable"
     * gives the name of the method to be called. The second-level key "parameters" is an
     * array of the arguments passed to "callable".
     * should be the names of the methods of $bridged which accomplish a payment state
     * change. If left false, a method will simply change the state of the Payment and
     * return true.
     *
     * @var array
     */
    private $methods = array(
    	'checkout' => false,
    	'process' => false,
    	'pend' => false,
    	'complete' => 'purchase',
    	'fail' => false,
    	'void' => 'void'
    );
    
    /**
     * Gateway options.
     *
     * Configuration options for the gateway. These are the variant options of different
     * gateways; e.g. Api Key, Api Secret Key, Username, etc. 
     *
     * @var array
     */
    private $options;
    
    /**
     * Constructor.
     *
     * @param array $sources
     * @param array $methods
     * @param array $availableOptions
     */
    public function __construct(array $sources, array $methods, array $availableOptions = null)
    {
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
}
