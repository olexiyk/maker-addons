<?php
namespace Olek\Ifttt\Conditions;
interface ConditionVerifierInterface
{
    /**
     * verify
     *
     * @return bool
     */
    public function runnable();

}
