<?php

namespace ActiveCampaign\Api\V1\Exceptions;

/**
 * Class RequestException
 */
class RequestException extends \Exception
{
    /**
     * Optional context for the exception
     *
     * @var array
     * @default []
     */
    private $context = array();

    /**
     * Gets the context
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }
}
