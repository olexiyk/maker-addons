<?php
// Sends a request to maker if it's a working day evening.

include 'vendor/autoload.php';

use Checkdomain\Holiday\Provider\DE;

$holidayChecked = new \Checkdomain\Holiday\Util();
$client         = new \GuzzleHttp\Client();

$url = 'https://maker.ifttt.com/trigger/leave/with/key/' . getenv('MAKER_KEY');
if (
    !$holidayChecked->isHoliday('DE', 'now', DE::STATE_BE)
    && isEvening()
    && isWeekday()
) {
    $res = $client->request('POST', $url, ['json' => ['value1' => 'left']]);
    echo 'request sent';
} else {
    echo 'request not sent';
}

/**
 * isEvening
 *
 * @return bool
 */
function isEvening()
{
    return date('H') >= 17 && date('H') <= 24;
}

/**
 * isWeekday
 *
 * @return bool
 */
function isWeekday()
{
    return date('N') < 6;
}
