<?php  
    //header("Content-Type: text/javascript");
		
?>

<?php  

	//echo "var folders = [];\n";
$folders = array();
$folders = glob('fotos/*', GLOB_ONLYDIR); // put all files in an array 
usort($folders, function ($a, $b) {
   return filemtime($a) < filemtime($b);
});
/*
foreach($folders as $i=>$folder) {
        echo "folders[".$i."] = {};\n";
		echo "folders[".$i."].modified = '".date('YmdHis', filemtime($folder))."';\n";
		$images = glob($folder."/{*.jpg, *.JPG, *.JPEG, *.png, *.PNG}", GLOB_BRACE);
		 if( count( $images ) > 0 ){
					$img = $images[3];
					echo "folders[".$i."].src = '".$img."';\n";
					echo "folders[".$i."].name = '".basename(dirname($img))."';\n";
		}
} 
*/
shuffle($folders);


$per_page = 20;
$page = $_GET['page']; // page=1, page=2...

// search
$search = $_GET['q']; // search term
$patron = "/\b$search\b/i";
$match = true;


$folders2 = array();
$folders_for = array();
if( $search )
	$folders_for = $folders;
else
	$folders_for = array_slice($folders, $page*$per_page, $per_page);


$index =0 ;
foreach ( $folders_for as $i=>$folder) {
	
		if( $search )
			$match = preg_match( $patron, basename($folder)  );
	
		$images = glob($folder."/{*.[jJ][pP][gG], *.[jJ][pP][eE][gG], *.[pP][nN][gG], *.[gG][iI][fF]}", GLOB_BRACE);
		 if( count( $images ) > 0 and $match ){
					$img = $images[0];
					$folders2[$index][] = '{}';
					$folders2[$index]['folder']  = basename(dirname($img));;
					$folders2[$index]['modified'] = date(DATE_ATOM, filemtime($folder) );
					$folders2[$index]['src']  = $img;
					$folders2[$index]['name']  = str_replace('-', ' ', basename(dirname($img)) );
					foreach( explode("-", basename(dirname($img))) as $i=>$val ){
						if( strlen( $val ) > 2 )
							$folders2[$index]['tags'][$i]  = $val; 
					}
					
					$index++;
		}
}


header('Content-type: application/json');
echo json_encode( $folders2 );







  ?>
  
