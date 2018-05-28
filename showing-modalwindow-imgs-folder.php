<?php
$folders = array();
if( $folder= $_GET['folder'] ) {
	$images = glob("fotos/$folder/{*.[jJ][pP][gG], *.[jJ][pP][eE][gG], *.[pP][nN][gG], *.[gG][iI][fF]}", GLOB_BRACE);
	

	echo "<md-dialog aria-label='Show pictures of $folder' class='fullscreen-dialog'>";
			echo "<md-toolbar md-scroll-shrink>
							<div class='md-toolbar-tools'>
							  <h3>
								<span style='text-transform: capitalize;'>{{name}}</span>  
								<a href='https://twitter.com/intent/tweet?url=http://migaleriadefotos.com/showing-my-naked-cameltoe/$folder&text={{name}}&hashtags=' target='_blank'><img ng-src='twitter-icon.png' height='25'></a>
								
								<a href='whatsapp://send?text=http://migaleriadefotos.com/foto/$folder'><img ng-src='icon-whatsapp.png' height='25'></a>								
								
								<a href='https://telegram.me/share/url?url=http://migaleriadefotos.com/showing-my-naked-cameltoe/$folder'><img ng-src='share_white-telegram.png' height='25'></a>
							  </h3>
							  
							  
							  <span flex></span>
							  <md-button ng-click='closeDialog()'>close</md-button>
							</div>
					</md-toolbar>";
				echo "<md-content>";
				echo "<md-dialog-content>";
					foreach( $images as $i=>$image) {
						echo "<center><img ng-src='$image' alt='{{name}}' style='max-width: 100%;height: auto;'></center>";
					}
				echo "</md-dialog-content>";
				echo "</md-content flex>";
	echo "</md-dialog>";
	
	
	
}

?>
