<?php
include('connect.php');
$floor = fa("select * from `floor` where `id`='$_GET[id]'");
$books = faa("select * from `book` where `classId`='$floor[id]' or `floorId`='$floor[id]'");
if ($books)
{
	if ($floor['parent'])
	{
		echo "會議室已被預約，預約時段：";
		foreach ($books as $book)
		{
			echo "\n" . str_replace('-', '/', $book['date']) . " $book[time]";
		}
		echo "\n是否關閉預約室？";
	}
	else
	{
		$classes = faa("select * from `floor` where `parent`='$floor[id]'");
		echo "該樓層中有會議室已被預約\n";
		foreach ($classes as $class)
		{
			$books = faa("select * from `book` where `classId`='$class[id]'");
			if ($books)
			{
				echo "\n$class[name]已被預約，預約時段：";
				foreach ($books as $book)
				{
					echo "\n" . str_replace('-', '/', $book['date']) . " $book[time]";
				}
			}
		}
		echo "\n\n是否刪除該樓層？";
	}
}