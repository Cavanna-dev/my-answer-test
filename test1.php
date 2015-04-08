<?php

$path = $argv[1];
$words = array_map('trim', file($path));

$input = stream_get_contents(STDIN);

preg_match_all('#[^ \n]+#', $input, $m);
$inputWords = $m[0];

foreach ($inputWords as $inputWord) {
    if (in_array($inputWord, $words)) {
        printf("%s\n", $inputWord);
    } else {
        printf("<%s>\n", $inputWord);
    }
}
