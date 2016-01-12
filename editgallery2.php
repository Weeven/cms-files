<?php
require('settings.php');
require('check.php');

$GalleryID = $_POST['gid'];
$GalleryName = $_POST['galname'];
$GalleryDesc = $_POST['galdesc'];

function filterFunction ($var) { 
    $var = nl2br(htmlspecialchars($var));
    $var = eregi_replace("'", "&#39;", $var);
    $var = eregi_replace("`", "&#39;", $var);		
    return $var; 
} 
$GalleryName = filterFunction($GalleryName);
$GalleryDesc = filterFunction($GalleryDesc);

require_once "../scripts/connect.php";


$sql = <<<SQL
	UPDATE
	tblGalleries
	SET galleryName = "$GalleryName", galleryDesc = "$GalleryDesc" WHERE galleryID = "$GalleryID" LIMIT 1
SQL;

if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
$result->free();
$db->close();
}

$_SESSION['updategallery'] = "True";

header("Location: $AdminUrl/admin-gallery-edit/". $GalleryID);
exit();
?>