<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<?php 

require_once('products.php');


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webscrap";
$conn = new mysqli($servername, $username, $password, $dbname);


$url="https://www.darty.com".$url->nodeValue ;	
//$url ="https://www.darty.com/nav/achat/accessoires/image_video_son/philips_cd-r_90_ms_jc_x10.html";
	
     
	
	if (@file_get_contents($url,true)=== false) {
    echo "error" ;//erreur de lecture : page indisponible
	 
  echo products::error_lecture ($urlp);
    }


	else { 
$htmld =file_get_contents($url,true); }

    $docd = new DOMDocument();
	if(!empty($htmld))  //vérifier si le résultat est correct du get_content (not empty) 
	{
	libxml_use_internal_errors(TRUE); 
	libxml_clear_errors(); 
	$docd->loadHTML($htmld);
	 $xpathd = new DOMXPath($docd); 
	 $search = array("\t", "\n", "\r" ,'"',"'"); //eliminer les retours à la ligne ,tabulations .. 
	 $stock = $xpathd->query('//meta[@name="product_stock"]/@content')->item(0)->nodeValue; //disponibilite du produit
	 $brand= $xpathd->query('//meta[@name="product_brand"]/@content')->item(0)->nodeValue; //la marque du produit
     $stock = str_replace($search, '', $stock);
	 $nom= $xpathd->query('//span[@class="product_name font-2-b"]')->item(0)->nodeValue; // nom 
	 $nom = str_replace($search, ' ', $nom);
	 $prix= $xpathd->query('//span[@class="darty_prix darty_mediumbig"]')->item(0)->nodeValue; //son prix actuel
     $prixsu= preg_replace('/[^0-9,]/', '', $prix); //Recuperation du prix sans la monnaie
	 $monnaie=str_replace($prixsu, "", $prix);
	 $categorie= $xpathd->query('//a[@class="product_family font-2"]')->item(0)->nodeValue; //categorie produit
	 $categorie=str_replace("Ã©",'e',$categorie);
	 $web_site= $xpathd->query('//meta[@property="og:site_name"]/@content')->item(0)->nodeValue; //website du produit;
	 $code_product_site = $xpathd->query('//th[text() = "Code"]/following-sibling::td[1]')->item(0)->nodeValue; //code du produit dans le site
     if(strpos($monnaie, "â‚¬") !== false) //recuperation de la monnaie
        {$monnaie="€";}
	 $prixssu=$xpathd->query('//meta[@name="product_unitprice_ttc"]/@content')->item(0)->nodeValue; //la marque du produit
     $img_URLS=array(); 
     $imgx= $xpathd->query('//img[@itemprop="image"]/@data-src'); // toutes les images du produits
	 foreach($imgx as $type){
			$img_URLS[] = $type->nodeValue;}
	 $url_im=array();
	 $url_im[0]="";
	 $url_im[1]="";
	 $url_im[2]="";
	 $url_im[3]="";
	 $url_im[4]="";
if (sizeof($img_URLS)>=5)
	{$url_im[0]=$img_URLS[0];
	$url_im[1]=$img_URLS[1];
	$url_im[2]=$img_URLS[2];
	$url_im[3]=$img_URLS[3];
	$url_im[4]=$img_URLS[4];
	}
else 
	{$k=0;
	 foreach($img_URLS as $im)
			{
			$url_im[$k]=$im;
			$k=$k+1;                  
			}			
	}
/****************************Insertion dans la BD**************************/	
echo products::insertp($nom ,$brand,$web_site,$code_product_site,$categorie,$stock,$monnaie,$prixssu,$url,$url_im);



	     
	
	
	}
	
?>