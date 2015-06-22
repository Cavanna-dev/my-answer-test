<?php

function getWords($file) {
    $data = file_get_contents($file);
    preg_match_all('#[^ \n]+#', $data, $m);
    return $m[0];
}

$words = getWords($argv[1]);
$inputWords = getWords($argv[2]);

foreach ($inputWords as $inputWord) {
    if (in_array($inputWord, $words)) {
        printf("%s\n", $inputWord);
    } else {
        printf("<%s>\n", $inputWord);
    }
}
