<meta http-equiv="Content-Type" content="text/html;
      charset=UTF-8" />
<?php
$urls=array();

$html = file_get_contents("https://www.darty.com/nav/recherche/philips.html");  

$doc = new DOMDocument();
libxml_use_internal_errors(TRUE); 
if(!empty($html)){ 

	$doc->loadHTML($html);
	libxml_clear_errors(); 
	$xpath = new DOMXPath($doc);
	$nbprod=$xpath->query('//div[@class="list_number_product"]')->item(0)->nodeValue;
	$nbprod= preg_replace('/[^0-9]/', '', $nbprod);
	$nbproduits=(int)$nbprod;
	$urls=array();
		$i=0;
    for ($x = 0; $x <= $nbproduits; $x+50)
	{	

$url="https://www.darty.com/nav/recherche?p=50&s=relevence&text=philips&o=".$x."";
echo $url ;
	$html = file_get_contents($url);
if(!empty($html))
{ 	
	
	$doc = new DOMDocument();
	$doc->loadHTML($html);
	libxml_clear_errors(); 
	$xpath = new DOMXPath($doc);
	$urlproduit = $xpath->query('//div[@class="prd-name"]/a/@href');
	$x=$x+50;

	
	

	foreach($urlproduit as $url){
		
			echo "Produit".$i." : https://www.darty.com".$url->nodeValue ."<br>"; 
			$urls[] = "https://www.darty.com".$url->nodeValue;  //les urls de tous les produits
			$i=$i+1;
			
			include "darty_product.php"; // executer le fichier darty_product pour chaque url de produit
			
		}
	
	
	}
	}

}


 
   
	 
  
	
	 
	
	



?>