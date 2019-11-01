<?php

namespace Pool\Http;

class Request
{
    /** @var array */
    private $attributes;
    /** @var string */
    private $httpMethod;
    /** @var string */
    private $requestUri;

    /**
     * Initialize Request object with the fields contained in the REQUEST superglobal
     * 
     * @return Request
     */
    public static function initialize(): Request
    {
        $obj = new static();
        $obj->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $obj->requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        foreach ($_REQUEST as $key => $value) {
            $obj->setAttribute($key, $value);
        }

        return $obj;
    }

    /**
     * Get an attribute value
     * If passed identifier doesn't exist, return default value
     * 
     * @param string $identifier Attribute name
     * @param mixed|null $default Fallback value
     * @return mixed
     */
    public function getAttribute(string $identifier, $default = null): mixed
    {
        if (key_exists($identifier, $this->attributes)) {
            return $this->attributes[$identifier];
        }

        return $default;
    }

    /**
     * Set an attribute with a value
     * 
     * @param string $identifier Attribute name
     * @param mixed|null $value Attribute value
     * @return null
     */
    public function setAttribute(string $identifier, $value = null): void
    {
        $this->attributes[$identifier] = $value;
    }
}
