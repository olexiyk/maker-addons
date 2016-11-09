<?php
namespace Olek\Ifttt\Conditions;
class WifiDisconnected implements ConditionVerifierInterface
{
    /** @var array */
    private $params;

    /**
     * WifiDisconnected constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * verify
     *
     * @return bool
     */
    public function runnable()
    {
        return "disconnected from" == $this->params["ConnectedToOrDisconnectedFrom"];
    }
}
