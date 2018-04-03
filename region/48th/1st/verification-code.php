<?php

include('config.php');

$characters = array_merge(range('0', '9'), range('A', 'Z'), range('a', 'z'));
shuffle($characters);

$result = array_slice($characters, 0, 4);
echo join('', $result) . ';';

$ascend = rand(0, 1);
echo $ascend ? 'ascend' : 'descend';

usort($result, function ($a, $b) use ($ascend) {
    return $ascend ? ord($a) <=> ord($b) : ord($b) <=> ord($a);
});

$_SESSION['verification_code'] = join('', $result);

