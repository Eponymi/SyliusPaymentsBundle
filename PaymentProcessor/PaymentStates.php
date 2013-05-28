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

/**
 * State constants for Payment
 */
final class PaymentStates
{
    const STATE_CHECKOUT   = 1;
    const STATE_PROCESSING = 2;
    const STATE_PENDING    = 3;
    const STATE_COMPLETED  = 4;
    const STATE_FAILED     = 5;
    const STATE_NEW        = 6;
    const STATE_VOID       = 7;
}
