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
 * Payment Processor Interface.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
interface PaymentProcessorInterface
{   
    /**
     * Execute a callable which determines if the state of the payment should be changed.
     *
     * @param string $method
     * @param \Sylius\Bundle\PaymentsBundle\Model\PaymentInterface $payment
     */
    public function run($method, Payment $payment);
    
    /**
     * Determine whether the payment should be pushed to the processing stage. 
     *
     * @param \Sylius\Bundle\PaymentsBundle\Model\PaymentInterface $payment
     * @return bool
     */
    public function processing(Payment $payment);
    
    /**
     * Determine whether the payment should be pushed to the pending stage.
     *
     * @param \Sylius\Bundle\PaymentsBundle\Model\PaymentInterface $payment
     * @return bool
     */
    public function pending(Payment $payment);
    
    /**
     * Determine whether the payment should be pushed to the completed stage.
     *
     * @param \Sylius\Bundle\PaymentsBundle\Model\PaymentInterface $payment
     * @return bool
     */
    public function completed(Payment $payment);
    
    /**
     * Determine whether the payment should be pushed to the void stage.
     *
     * @param \Sylius\Bundle\PaymentsBundle\Model\PaymentInterface $payment
     * @return bool
     */
    public function void(Payment $payment);
    
    /**
     * Determine whether the payment should be pushed to the failed stage.
     *
     * @param \Sylius\Bundle\PaymentsBundle\Model\PaymentInterface $payment
     * @return bool
     */
    public function failed(Payment $payment);
}
