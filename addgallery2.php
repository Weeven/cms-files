<?php
require('settings.php');

$galleryName = $_POST['galname'];
$galleryDesc = $_POST['galdesc'];
date_default_timezone_set('Europe/Dublin');					
$galleryDate = date( "Y-m-d H:i:s" );

function filterFunction ($var) { 
    $var = nl2br(htmlspecialchars($var));
    $var = eregi_replace("'", "&#39;", $var);
    $var = eregi_replace("`", "&#39;", $var);
    return $var; 
} 
$galleryName = filterFunction($galleryName);
$galleryDesc = filterFunction($galleryDesc);

require_once "../scripts/connect.php";

$sql = <<<SQL
	INSERT INTO
	tblGalleries
	(galleryName, galleryDesc, galleryDate)
	VALUES
	("$galleryName", "$galleryDesc", "$galleryDate")
SQL;

if (!$result = $db->query($sql))
{
	die('There was an error running the query [' . $db->error . ']');
}

header("Location: $AdminUrl/admin-gallery");
exit();
?>