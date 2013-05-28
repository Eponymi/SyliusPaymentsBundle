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

/**
 * Default Payment Processor.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class DefaultPaymentProcessor extends AbstractPaymentProcessor
{   
    /**
     * {@inheritdoc}
     */
    public function processing(Payment $payment)
    {
    	return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function pending(Payment $payment) 
    {
    	return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function completed(Payment $payment) 
    {
    	return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function void(Payment $payment)
    {
    	return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function failed(Payment $payment)
    {
    	return true;
    }
}
