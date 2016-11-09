<?php
namespace Olek\Ifttt\Conditions;

use Checkdomain\Holiday\Provider\DE;

class WorkingDay implements ConditionVerifierInterface
{
    /** @var \Checkdomain\Holiday\Util */
    private $holidayChecker;

    /**
     * WorkingDay constructor.
     *
     * @param $holidayChecker
     */
    public function __construct($holidayChecker)
    {
        $this->holidayChecker = $holidayChecker;
    }

    /**
     * verify
     *
     * @return bool
     */
    public function runnable()
    {
        return !$this->holidayChecker->isHoliday('DE', 'now', DE::STATE_BE);
    }
}
