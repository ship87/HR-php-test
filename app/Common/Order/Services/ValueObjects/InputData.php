<?php

namespace App\Common\Order\Services\ValueObjects;

/**
 * Class InputData - данные для формы заказа
 * @package App\Common\Order\Services\ValueObjects
 */
class InputData
{
    /**
     * @var int
     */
    protected $partnerId;

    /**
     * @var string
     */
    protected $clientEmail;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var array
     */
    protected $products;

    /**
     * @var array
     */
    protected $productsSrc;

    /**
     * @return int
     */
    public function getPartnerId(): int
    {
        return $this->partnerId;
    }

    /**
     * @param int $partnerId
     */
    public function setPartnerId(int $partnerId)
    {
        $this->partnerId = $partnerId;
    }

    /**
     * @return string
     */
    public function getClientEmail(): string
    {
        return $this->clientEmail;
    }

    /**
     * @param string $clientEmail
     */
    public function setClientEmail(string $clientEmail)
    {
        $this->clientEmail = $clientEmail;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products)
    {
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getProductsSrc(): array
    {
        return $this->productsSrc;
    }

    /**
     * @param array $productSrc
     */
    public function setProductsSrc(array $productsSrc)
    {
        $this->productsSrc = $productsSrc;
    }
}