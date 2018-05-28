<?php
$folders = array();
if( $folder= $_GET['folder'] ) {
	$images = glob("fotos/$folder/{*.[jJ][pP][gG], *.[jJ][pP][eE][gG], *.[pP][nN][gG], *.[gG][iI][fF]}", GLOB_BRACE);
	

	$folders2 = array();
    $index = 0;
	if( count( $images ) > 0  ){
			$img = $images[0];
			$folders2[0]['info']['folder']  = basename(dirname($img));;
			$folders2[0]['info']['modified'] = date(DATE_ATOM, filemtime($folder) );
			$folders2[0]['info']['mainsrc']  = $img;
			$folders2[0]['info']['name']  = str_replace('-', ' ', basename(dirname($img)) );
			foreach( $images as $i=>$image) {
				$folders2[0]['srcs'][$i]  = $image; 
			}
			foreach( explode("-", basename(dirname($img))) as $i=>$val ){
				if( strlen( $val ) > 2 )
					$folders2[0]['tags'][$i]  = $val; 
			}
			
			$index++;
	}

	header('Content-type: application/json');
	echo json_encode( $folders2 );

}

?>
