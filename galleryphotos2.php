<?php
require('settings.php');
require_once "../scripts/connect.php";
$GalleryID = ereg_replace("[^0-9]", "", $_POST['gid']);

if(isset($_FILES['files'])){
    $errors= array();
	
	function img_resize($target, $newcopy, $w, $h, $ext) {
		list($w_orig, $h_orig) = getimagesize($target);
		$scale_ratio = $w_orig / $h_orig;
		if (($w / $h) > $scale_ratio) {
			   $w = $h * $scale_ratio;
		} else {
			   $h = $w / $scale_ratio;
		}
		$img = "";
		$ext = strtolower($ext);
		if ($ext == "gif"){ 
		  $img = imagecreatefromgif($target);
		} else if($ext =="png"){ 
		  $img = imagecreatefrompng($target);
		} else { 
		  $img = imagecreatefromjpeg($target);
		}
		$tci = imagecreatetruecolor($w, $h);
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
		imagejpeg($tci, $newcopy, 80);
	}
	
	foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
		$file_name = $_FILES['files']['name'][$key];
		$file_name = preg_replace('#[^a-z.0-9]#i', '', $file_name);
		
		while (file_exists("$upload_path"."$file_name")) {
			$random_digit=rand(0000,9999);
			$extension_pos = strrpos($file_name, '.');
			$file_name = substr($file_name, 0, $extension_pos) . "(" .$random_digit . ")" . substr($file_name, $extension_pos);
		}
		
		$Thumbextension_pos = strrpos($file_name, '.');
		$thumb_name = substr($file_name, 0, $Thumbextension_pos) . "-sm" . substr($file_name, $Thumbextension_pos);
		
		$fileTmpLoc = $_FILES["files"]["tmp_name"][$key];
		$fileType = $_FILES["files"]["type"][$key];
		$fileSize = $_FILES["files"]["size"][$key];
		$fileErrorMsg = $_FILES["files"]["error"];
		
		$kaboom = explode(".", $file_name);
		$fileExt = end($kaboom);
		
		if (!$fileTmpLoc) {
			print "<p class='update'>Please select a file.</p>";
		} else if($fileSize > 2097152) {
			unlink($fileTmpLoc);
			print "<div class='up-list-fail'>{$file_name} - <font color='#ff0000'>File too large (Over 2MB)</font><span></span></div>";
		} else if (!preg_match("/.(gif|jpg|png)$/i", $file_name) ) {
			unlink($fileTmpLoc);
			print "<div class='up-list-fail'>{$file_name} - <font color='#ff0000'>File must be jpg, gif or png</font><span></span></div>";
		} else if ($fileErrorMsg == 1) {
			print "<div class='up-list-fail'>{$file_name} - <font color='#ff0000'>Error, please try again</font><span></span></div>";
		} else {
		
			$ImageLoc = $upload_path.$file_name;
				
$sql = <<<SQL
INSERT
into tblPhotos
(galleryID, imageLoc, imageTitle, imageThumb)
VALUES
("$GalleryID", "$ImageLoc" , "$file_name" , "$thumb_name")
SQL;

if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
$result->free();
$db->close();
}
		
			$moveResult = move_uploaded_file($fileTmpLoc, "$upload_path/$file_name");
			if ($moveResult != true) {
				unlink($fileTmpLoc);
				print "<p class='update'>There was an error during the upload, please try again.</p>";
				print "<style>.combover { display: none !important; }</style>";
				exit();
			}
			
			$target_file = "$upload_path/$file_name";
			$extension_pos = strrpos($file_name, '.');
			$file_name = substr($file_name, 0, $extension_pos) . substr($file_name, $extension_pos);
			$file_name_sm = substr($file_name, 0, $extension_pos) . "-sm" . substr($file_name, $extension_pos);
			$resized_file = "$upload_path/$file_name";
			$resized_file_sm = "$upload_path/$file_name_sm";
			$wmax = 200;
			$hmax = 200;
			$wmaxl = 800;
			$hmaxl = 800;
			img_resize($target_file, $resized_file_sm, $wmax, $hmax, $fileExt);
			img_resize($target_file, $resized_file, $wmaxl, $hmaxl, $fileExt);
		
			print "<div class='up-list'>{$file_name}<span></span></div>";
			
		}
		
    }
	
} else {
    print "<p class='update'>There was an error during the upload, please try again.</p>";
}

print "<style>.combover { display: none !important; }</style>";
?>