<?php

$conf = json_decode(file_get_contents('.conf.json'), true);

if (empty($conf['startDate'])) {
    echo 'You must specify "startDate" config param' . PHP_EOL;
    return;
}

if (empty($conf['chart'])) {
    echo 'You must specify "chart" config param' . PHP_EOL;
    return;
}

$timeZone = new DateTimeZone('UTC');
$currentDate = new DateTime($conf['startDate'], $timeZone);

$daysCount = count($conf['chart']) * count($conf['chart'][0]);

for ($i = 0; $i < $daysCount; $i++) {
    echo $currentDate->format('Y-m-d H:i:s') . PHP_EOL;

    $currentDate->add(new DateInterval('P1D'));
}
