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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Payments model.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class Payment implements PaymentInterface
{
    /**
     * Payments method identifier.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Method.
     *
     * @var PaymentMethodInterface
     */
    protected $method;

    /**
     * Currency.
     *
     * @var string
     */
    protected $currency;

    /**
     * Amount.
     *
     * @var integer
     */
    protected $amount;

    /**
     * Children.
     *
     * @var Collection
     */
    protected $children;
    
    /**
     * Parent.
     *
     * @var Payment
     */
    protected $parent;

    /**
     * Credit card as a source.
     *
     * @var CreditCardInterface
     */
    protected $creditCard;

    /**
     * Creation date.
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Last update time.
     *
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->amount = 0;
        $this->children = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function getMethod()
    {
      return $this->method;
    }

    public function setMethod(PaymentMethodInterface $method = null)
    {
        $this->method = $method;

        return $this;
    }

    public function setSource(PaymentSourceInterface $source = null)
    {
        if (null === $source) {
            $this->creditCard = null;
        }

        if ($source instanceof CreditCardInterface) {
            $this->creditCard = $source;
        }

        return $this;
    }

    public function getSource()
    {
        if (null !== $this->creditCard) {
            return $this->creditCard;
        }
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount()
    {
      return $this->amount;
    }

    public function setAmount($amount)
    {
      $this->amount = $amount;

      return $this;
    }
    
    public function getParent(){
      return $this->parent;
    }
    
    public function setParent(PaymentInterface $parent = null)
    {
      $this->parent = $parent;
      
      return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function hasChild(PaymentInterface $child)
    {
        return $this->children->contains($child);
    }
    
    public function removeChild(PaymentInterface $child)
    {
        if ($this->hasChild($child)) {
            $child->setParent(null);
            $this->children->removeElement($child);
        }
    }
    
    public function addChild(PaymentInterface $child)
    {
      if (!$this->hasChild($child)) {
        $child->setParent($this);
        $this->children->add($child);
      }

      return $this;
    }

    public function getBalance()
    {
        $total = 0;

        foreach ($this->children as $payment) {
            $total += $payment->getAmount();
        }

        return $this->amount - $total;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
