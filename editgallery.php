<?php
require('settings.php');
require('check.php');
$PageID = "gallery";

$UpdateGallery = $_SESSION['updategallery'];
$GalleryID = ereg_replace("[^0-9]", "", $_GET['GalleryID']);

require_once "../scripts/connect.php";

$sql = <<<SQL
	SELECT *
	FROM tblGalleries
	WHERE GalleryID = "$GalleryID" LIMIT 1
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
$GalleryName = $row["galleryName"];
$GalleryOrder = $row["galleryOrder"];
$GalleryDesc = $row["galleryDesc"];
$GalleryLive = $row["Live"];
} 

$result->free();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require 'head.php'; ?>
</head>

<body>
	
    <?php require 'top.php'; ?>
    <?php require 'left.php'; ?>
    
	<div class="content">
    	
        <a class="grn-btn-3" href="<?php echo ($AdminUrl) ?>/admin-gallery"><span class="left"></span><img src="<?php echo ($AdminUrl) ?>/images/icon-back.png" />Back<span class="right"></span></a>
        
        <h2>Gallery</h2>
        <div class="inner">
            <h3>Edit Gallery</h3>
            <div class="inner2">
            	<?php if ($UpdateGallery == "True") { ?>
                <p class="update">The gallery has been updated.</p>
              	<?php }; ?>
                <form class="settings" id="form" name="changepass" action="<?php echo ($AdminUrl) ?>/editgallery2.php" onSubmit="return Form_Validator()" method="post">
                    <ol>
                        <li>
                            <label>gallery name:</label>
                            <input class="normal" name="galname" type="text" id="galname" maxlength="24" value="<?php echo $GalleryName; ?>" />
                        </li>
                        <li>
                            <label>gallery description:</label>
                            <textarea name="galdesc" rows="4" id="galdesc"><?php echo $GalleryDesc; ?></textarea>
                        </li>
                        <li>
                            <label></label>
                            <input name="gid" type="hidden" value="<?php echo $GalleryID; ?>" />
                            <input class="grn-btn-2 mt10" type="submit" name="button" id="button" value="Update Gallery" />
                        </li>
                    </ol>
                </form>
                <?php unset($_SESSION['updategallery']);?>
            </div> <!--end inner2-->
       	</div> <!--end inner-->
        
    </div> <!--end content-->

</body>
</html>