<?php
namespace Olek\Ifttt\Conditions;
class WeekDay implements ConditionVerifierInterface
{

    /**
     * verify
     *
     * @return bool
     */
    public function runnable()
    {
        return date('N') < 6;
    }
}
