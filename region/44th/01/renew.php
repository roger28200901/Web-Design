<?php
include('connect.php');
$nos = range(0, 9);
$no4 = array_rand($nos, 4);
foreach ($no4 as $no)
{
	$result .= $no;
}
shuffle($no4);
foreach ($no4 as $no)
{
	echo $no;
}
echo $result;