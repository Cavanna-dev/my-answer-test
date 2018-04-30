<?php

function readWords($file)
{
    $data = file_get_contents($file);
    preg_match_all('#[^ \n]+#', $data, $m);
    return $m[0];
}

function setArrayValueToKey(array $arrayValues)
{
    $result = [];

    foreach ($arrayValues as $arrayValue) {
        $result[$arrayValue] = $arrayValue;
    }

    return $result;
}

$validWords = readWords($argv[1]); // Words
$validWords = setArrayValueToKey($validWords);
$inputWords = readWords($argv[2]); // test1.in

foreach ($inputWords as $key => $inputWord) {
    if (isset($validWords[$inputWord])) {
        printf("%s\n", $inputWord);
    } else {
        printf("<%s>\n", $inputWord);
    }
}
