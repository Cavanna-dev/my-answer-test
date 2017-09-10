<?php

function readWords($file) {
    $data = file_get_contents($file);
    preg_match_all('#[^ \n]+#', $data, $m);
    return $m[0];
}

$validWords = readWords($argv[1]);
$inputWords = readWords($argv[2]);

foreach ($inputWords as $inputWord) {
    if (in_array($inputWord, $validWords)) {
        printf("%s\n", $inputWord);
    } else {
        printf("<%s>\n", $inputWord);
    }
}
