<div class="leftbar">
    <div class="leftinner">
    	<ul>
        	<li><a <?php if ($PageID == "home"){ echo ("class='active'");};?> href="<?php echo ("$AdminUrl"); ?>/admin-home">Home</a></li>
            <li><a <?php if ($PageID == "pages"){ echo ("class='active'");};?> href="<?php echo ("$AdminUrl"); ?>/admin-pages">Pages</a></li>
            <li><a <?php if ($PageID == "gallery"){ echo ("class='active'");};?> href="<?php echo ("$AdminUrl"); ?>/admin-gallery">Gallery</a></li>
            <li><a <?php if ($PageID == "settings"){ echo ("class='active'");};?> href="<?php echo ("$AdminUrl"); ?>/admin-settings">Settings</a></li>
       	</ul>
    </div>
</div>