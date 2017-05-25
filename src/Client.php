<?php namespace CoreProc\Paynamics\Paygate;

use Exception;

class Client implements ClientInterface
{
    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $merchantKey;

    /**
     * @var string
     */
    private $productionUrl;

    /**
     * @var bool
     */
    private $sandbox;

    /**
     * @var string
     */
    private $sandboxUrl;

    /**
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Sets configuration
     *
     * @param array $config
     * @return self
     */
    public function setConfig(array $config)
    {
        if (isset($config['merchant_id'])) {
            $this->setMerchantId($config['merchant_id']);
        }
        if (isset($config['merchant_key'])) {
            $this->setMerchantKey($config['merchant_key']);
        }
        if (isset($config['sandbox'])) {
            $this->setSandbox($config['sandbox']);
        }
        if (isset($config['sandbox_url'])) {
            $this->sandboxUrl = $config['sandbox_url'];
        }
        if (isset($config['production_url'])) {
            $this->productionUrl = $config['production_url'];
        }

        return $this;
    }

    /**
     * Returns the request URL to be used. Depends if sandbox or production.
     *
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->isSandbox() ? $this->sandboxUrl : $this->productionUrl;
    }

    /**
     * Returns if sandbox or production.
     *
     * @return bool
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * Sets if sandbox or production.
     *
     * @param bool $sandbox
     * @return self
     * @throws Exception
     */
    public function setSandbox($sandbox = false)
    {
        if ( ! is_bool($sandbox)) {
            throw new Exception("Sandbox value should be boolean");
        }

        $this->sandbox = $sandbox;

        return $this;
    }

    /**
     * Returns assigned Merchant ID
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Returns assigned Merchant Key
     *
     * @return string
     */
    public function getMerchantKey()
    {
        return $this->merchantKey;
    }

    /**
     * Sets Merchant ID
     *
     * @param $merchantId
     * @return self
     * @throws Exception
     */
    public function setMerchantId($merchantId)
    {
        if ( ! is_string($merchantId)) {
            throw new Exception("Merchant ID should be string");
        }

        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Sets Merchant Key
     *
     * @param $merchantKey
     * @return self
     * @throws Exception
     */
    public function setMerchantKey($merchantKey)
    {
        if ( ! is_string($merchantKey)) {
            throw new Exception("Merchant Key should be string");
        }

        $this->merchantKey = $merchantKey;

        return $this;
    }

    /**
     * Create new request
     *
     * @param RequestBodyInterface $requestBody
     * @return RequestInterface
     */
    public function createRequest(RequestBodyInterface $requestBody)
    {
        return new PaygateRequest($this, $requestBody);
    }

    /**
     * Create new request and execute
     *
     * @param RequestBodyInterface $requestBody
     * @return string
     */
    public function send(RequestBodyInterface $requestBody)
    {
        return $this->createRequest($requestBody)->generateForm();
    }
}