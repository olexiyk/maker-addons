<?php
namespace Olek\Ifttt;

use Olek\Ifttt\Actions\ActionRunnerInterface;
use Olek\Ifttt\Conditions\ConditionVerifierInterface;

class Application
{
    /** @var  ConditionVerifierInterface[] */
    private $conditions;
    /** @var  ActionRunnerInterface[] */
    private $actions;

    /**
     * Application constructor.
     *
     * @param \Olek\Ifttt\Conditions\ConditionVerifierInterface[] $conditions
     * @param \Olek\Ifttt\Actions\ActionRunnerInterface[]         $actions
     */
    public function __construct(array $conditions, array $actions)
    {
        $this->conditions = $conditions;
        $this->actions    = $actions;
    }

    public function run()
    {
        $result = true;
        foreach ($this->conditions as $condition) {
            $runnable = $condition->runnable();
            if (!$runnable) {
                echo get_class($condition) . ' condition is not met';
            }
            $result = $result && $runnable;
        }

        if ($result) {
            foreach ($this->actions as $action) {
                $action->run();
            }
        }
    }
}
