<?php

$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

$folders = $folders2 = array();
$folders = glob('fotos*', GLOB_ONLYDIR); // put all files in an array 
usort($folders, function ($a, $b) {
   return filemtime($a) < filemtime($b);
});
$index = 0;
foreach ( $folders as $i=>$folder) {
		$images = glob($folder."/{*.[jJ][pP][gG], *.[jJ][pP][eE][gG], *.[pP][nN][gG], *.[gG][iI][fF]}", GLOB_BRACE);
		 if( count( $images ) > 0  ){
					$img = $images[0];
					$folders2[$index]['folder']  = $protocol.$_SERVER['HTTP_HOST'].'/foto/'.basename(dirname($img));
					$folders2[$index]['lastmod'] = date(DATE_ATOM, filemtime($folder) );
					$folders2[$index]['loc']  = $protocol.$_SERVER['HTTP_HOST'].'/'.$img;
					$folders2[$index]['caption']  = str_replace('-', ' ', basename(dirname($img)) );
					$index++;
		}
}




//create your XML document, using the namespaces
$xml = new DOMDocument("1.0", "UTF-8");
$xml_urlset = $xml->createElement('urlset');
$xml_urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$xml_urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
$xml_urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
$xml_urlset->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');


// Main page
$xml_url = $xml->createElement("url");
$xml_loc = $xml->createElement("loc",  $protocol.$_SERVER['HTTP_HOST'] );
$xml_lastmod = $xml->createElement("lastmod",   date('c',time()) );
$xml_changefreq = $xml->createElement("changefreq",  'daily');
$xml_priority = $xml->createElement("priority",  '1.0');
$xml_url->appendChild($xml_loc);
$xml_url->appendChild($xml_lastmod);
$xml_url->appendChild($xml_changefreq);
$xml_url->appendChild($xml_priority);
$xml_urlset->appendChild($xml_url);

// URL galleries
foreach ( $folders2 as $item ) {
    //add the page URL to the XML urlset
	$xml_url = $xml->createElement("url");
	
	$xml_loc = $xml->createElement("loc",  $item['folder']  );
	$xml_url->appendChild($xml_loc);
	
	$xml_image = $xml->createElement("image:image");
	$xml_loc_image = $xml->createElement("image:loc",  $item['loc']  );
	$xml_caption_image = $xml->createElement("image:caption",  $item['caption']  );
	$xml_image->appendChild($xml_loc_image);
	$xml_image->appendChild($xml_caption_image);
	$xml_url->appendChild($xml_image);
	
	$xml_urlset->appendChild($xml_url);
}

$xml->appendChild($xml_urlset);

header("Content-type: text/xml; charset=utf-8");
$xml->formatOutput = true;
$xml->preserveWhiteSpace = false;
$xml->save('sitemap.xml');

// Compress sitemap.xml to gz file: sitemap.xml.gz
$data = implode("", file("sitemap.xml"));
$gzdata = gzencode($data, 9);
$fp = fopen("sitemap.xml.gz", "w");
fwrite($fp, $gzdata);
fclose($fp);
?>
