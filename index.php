<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console

include 'vendor/autoload.php';

use Checkdomain\Holiday\Provider\DE;

$holidayChecked = new \Checkdomain\Holiday\Util();

if (!$holidayChecked->isHoliday('DE', 'now', DE::STATE_BE) && isEvening()) {
    echo 'yes';
} else {
    echo 'no';
}

function isEvening()
{
    return date('H') >= 17 && date('H') <= 24;
}
