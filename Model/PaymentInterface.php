<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\PaymentsBundle\Model;

/**
 * Single payment interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
interface PaymentInterface
{
    const STATE_CHECKOUT   = 1;
    const STATE_PROCESSING = 2;
    const STATE_PENDING    = 3;
    const STATE_COMPLETED  = 4;
    const STATE_FAILED     = 5;
    const STATE_NEW        = 6;
    const STATE_VOID       = 7;

    /**
     * Get payments identifier.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get payment method associated with this payment.
     *
     * @return PaymentMethodInterface
     */
    public function getMethod();

    /**
     * Set payment method.
     *
     * @param PaymentMethodInterface $method
     */
    public function setMethod(PaymentMethodInterface $method = null);

    /**
     * Get payment source.
     *
     * @return PaymentSourceInterface
     */
    public function getSource();

    /**
     * Set payment source.
     *
     * @param null|PaymentSourceInterface $source
     */
    public function setSource(PaymentSourceInterface $source = null);

    /**
     * Get payment currency.
     *
     * @return string
     */
    public function getCurrency();

    /**
     * Set currency.
     *
     * @param string
     */
    public function setCurrency($currency);

    /**
     * Get amount.
     *
     * @return integer
     */
    public function getAmount();

    /**
     * Set amount.
     *
     * @param integer $amount
     */
    public function setAmount($amount);

    /**
     * Return the balance.
     *
     * @return integer
     */
    public function getBalance();
    
    /**
     * Get parent for this payment.
     *
     * @return PaymentInterface
     */
    public function getParent();
    
    /**
     * Set parent for this payment.
     *
     * @return PaymentInterface
     */
    public function setParent(PaymentInterface $parent = null);

    /**
     * Get all child payments for this payment.
     *
     * @return Collection
     */
    public function getChildren();

    /**
     * Add child payment to payment.
     *
     * @param PaymentInterface
     */
    public function addChild(PaymentInterface $child);

    /**
     * Remove child payment from payment.
     *
     * @param TransactionInterface
     */
    public function removeChild(PaymentInterface $child);

    /**
     * Has children?
     *
     * @return Boolean
     */
    public function hasChild(PaymentInterface $child);

    /**
     * Get creation time.
     *
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * Get last update time.
     *
     * @return DateTime
     */
    public function getUpdatedAt();
}
