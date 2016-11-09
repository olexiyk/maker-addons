<?php
namespace Olek\Ifttt\Conditions;
class Evening implements ConditionVerifierInterface
{

    /**
     * verify
     *
     * @return bool
     */
    public function runnable()
    {
        return date('H') >= 17 && date('H') <= 24;
    }
}
