<?php
require('settings.php');
require('check.php');
require_once "../scripts/connect.php";
$GalleryID = ereg_replace("[^0-9]", "", $_GET['GalleryID']);

$sql = <<<SQL
	DELETE
	FROM tblGalleries
	WHERE galleryID = "$GalleryID" LIMIT 1
SQL;

if (!$result = $db->query($sql))
{
	die('There was an error running the query [' . $db->error . ']');
	$result->free();
	$db->close();
}

header("Location: $AdminUrl/admin-gallery");
exit();
?>