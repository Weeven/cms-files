<?php
require('settings.php');
//require('check.php');
require_once "../scripts/connect.php";

$ImageID = ereg_replace("[^0-9]", "", $_GET['ImageID']);

require_once "../scripts/connect.php";

$sql = <<<SQL
	SELECT *
	FROM tblPhotos
	WHERE ImageID = "$ImageID" LIMIT 1
SQL;

if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
$result->free();
$db->close();
}

while($row = $result->fetch_assoc())
{
$GalleryID = $row["galleryID"];
$ImageTitle = $row["imageTitle"];
$ImageCaption = $row["captionText"];
$ImageThumb = $row["imageThumb"];
} 

$result->free();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require 'head.php'; ?>
<script type="text/javascript">
	function editPhoto() {
		var valImageTitle = document.getElementById('galname').value;
		var valImageCaption = document.getElementById('galdesc').value;
		$.post("<?php echo ("$AdminUrl"); ?>/editphotos2.php?ImageID=<?php echo ("$ImageID"); ?>&ImageTitle=" + valImageTitle + "&ImageCaption=" + valImageCaption + "", function(theResponse){
			$("#recordsArray_<?php echo ("$ImageID"); ?> .recordstitle").html(valImageTitle);
			$("#recordsArray_<?php echo ("$ImageID"); ?> .recordscaption").html(valImageCaption);
		});
		$(document).trigger('close.facebox');
	}
</script>
</head>

<body>
	
    <?php if ($UpdateImage == "True") { ?>
    <p class="update">The gallery has been updated.</p>
    <?php }; ?>
    <form class="settings" id="form" name="changepass" onSubmit="return Form_Validator()" method="post">
        <ol>
        	<li>
            	<label>image thumb:</label>
            	<?php
            		echo "<img src='" . $AdminUrl . "/" . $upload_path . $ImageThumb . "' alt='" . $ImageTitle . "' height='100'/>";
				 ?>
           	</li>
            <li>
                <label>image title:</label>
                <input class="normal" name="galname" type="text" id="galname" maxlength="24" value="<?php echo $ImageTitle; ?>" />
            </li>
            <li>
                <label>image caption:</label>
                <textarea name="galdesc" rows="4" id="galdesc"><?php echo $ImageCaption; ?></textarea>
            </li>
            <li>
                <label></label>
                <input name="gid" type="hidden" value="<?php echo $ImageID; ?>" />
                <input name="gid2" type="hidden" value="<?php echo $GalleryID; ?>" />
                <!--<input class="grn-btn-2 mt10" type="submit" name="button" id="button" value="Update Image" />-->
                
                <a class='grn-btn grn-btn-mar' href='javascript:void(0);' onclick='javascript:editPhoto("<?php echo ($ImageTitle) ?>");'>
                	<span class="left"></span>
                	Update Image
                    <span class="right"></span>
               	</a>
			</li>
        </ol>
    </form>
    <?php unset($_SESSION['UpdateImage']);?>
    
</body>
</html>