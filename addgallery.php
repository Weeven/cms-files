<?php
require('settings.php');
require('check.php');
require_once "../scripts/connect.php";
$PageID = "gallery";
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
            <h3>Add Gallery</h3>
            <div class="inner2">
                <form class="settings" id="form" name="changepass" action="<?php echo ($AdminUrl) ?>/addgallery2.php" onSubmit="return Form_Validator()" method="post">
                    <ol>
                        <li>
                            <label>gallery name:</label>
                            <input class="normal" name="galname" type="text" id="galname" maxlength="24" />
                        </li>
                        <li>
                            <label>gallery description:</label>
                            <textarea name="galdesc" rows="4" id="galdesc"></textarea>
                        </li>
                        <li>
                            <label></label>
                            <input class="grn-btn-2 mt10" type="submit" name="button" id="button" value="Add Gallery" />
                        </li>
                    </ol>
                </form>
            </div> <!--end inner2-->
       	</div> <!--end inner-->
        
    </div> <!--end content-->
     
</body>
</html>