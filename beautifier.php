<?php

if (null === ($conf = json_decode(file_get_contents('.conf.json'), true))) {
    echo 'Cannot decode .conf.json file' . PHP_EOL;
    return;
}

if (empty($conf['startDate'])) {
    echo 'You must specify "startDate" config param' . PHP_EOL;
    return;
}

if (empty($conf['chart'])) {
    echo 'You must specify "chart" config param' . PHP_EOL;
    return;
}

$daysCount = count($conf['chart']) * count($conf['chart'][0]);
$timeZone = new DateTimeZone('UTC');
$currentDate = new DateTime($conf['startDate'], $timeZone);
$dateTimeStr = $currentDate->format(DateTime::RFC2822);

for ($i = 0; $i < $daysCount; $i++) {
    $count = $conf['chart'][$i % 7][(int) ($i / 7)];

    for ($j = 0; $j < $count; $j++) {
        $currentDate->setTime(rand(0, 23), rand(0, 59), rand(0, 59));
        $dateTimeStr = $currentDate->format(DateTime::RFC2822);

        exec(<<<COMMAND
            git reset HEAD~1 && \
            GIT_AUTHOR_DATE="$dateTimeStr" \
            GIT_COMMITTER_DATE="$dateTimeStr" \
            git commit --all --message="$dateTimeStr" && \
            git push origin main --force --quiet
COMMAND
        );
    }

    echo $dateTimeStr . ' done with ' . $count . ' commits' . PHP_EOL;

    $currentDate->add(new DateInterval('P1D'));
}
