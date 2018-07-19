<?php
class products {
	
static public function error_lecture ($urlp){ //fonction qui detecte s 'il existe une erreur comme par exemple dans le lien suivant 
//lien avec erreur : https://www.darty.com/nav/achat/accessoires/image_video_son/philips_cd-r_90_ms_jc_x10.html
$conn = new mysqli("localhost", "root", "", "webscrap");
$sql0='SELECT * 
FROM product p
INNER JOIN website w
ON p.web_site = w.name
INNER JOIN  brand b
ON p.brand = b.name
WHERE p.url_product="'.$urlp.'"   ';

$result = $conn->query($sql0);

	if ($result->num_rows > 0) 	{
		 while($row = $result->fetch_assoc()) {
		$sql="INSERT INTO `history`(`id_product`, `id_website`, `id_brand`, `creation_date`, `last_scraping_date`, `price`, `stock`, 
		 `nb_error_lecture`)
		VALUES ('".$row["id_product"]."','".$row["id_web"]."','".$row["id_brand"]."','".date("Y-m-d h:i:sa")."','".date("Y-m-d h:i:sa")."',
		'".$row["price"]."','".$row["disponibility"]."',1)";
		
			if ($conn->query($sql) === TRUE) {echo "New record created successfully";}
	else {echo "Error: " . $sql . "<br>" . $conn->error;}
	}}	

	}



 static public function insert($fin,$code_product_site){ 
 //cas insertion nv enregistrement 
 $conn = new mysqli("localhost", "root", "", "webscrap");
$sql0='SELECT * 
FROM product p
INNER JOIN website w
ON p.web_site = w.name
INNER JOIN  brand b
ON p.brand = b.name
WHERE p.code_product_site="'.$code_product_site.'"   ';

$result = $conn->query($sql0);
if ($result->num_rows > 0) 	{
		 while($row = $result->fetch_assoc()) {
	if($fin==0){		 
	$sql="INSERT INTO `history`(`id_product`, `id_website`, `id_brand`, `creation_date`, `last_scraping_date`, 
 `price`, `stock`)
 VALUES ('".$row["id_product"]."','".$row["id_web"]."','".$row["id_brand"]."','".date("Y-m-d h:i:sa")."','".date("Y-m-d h:i:sa")."',
		'".$row["price"]."','".$row["disponibility"]."')";		 
	
	}
	else {
		$sql="INSERT INTO `history`(`id_product`, `id_website`, `id_brand`, `creation_date`, `last_scraping_date`, 
 `price`, `stock`,`date_end_sale`)
 VALUES ('".$row["id_product"]."','".$row["id_web"]."','".$row["id_brand"]."','".date("Y-m-d h:i:sa")."','".date("Y-m-d h:i:sa")."',
		'".$row["price"]."','".$row["disponibility"]."','".date("Y-m-d")."')";	
	}
	}
 if ($conn->query($sql) === TRUE) {echo "New record created successfully";}
	else {echo "Error: " . $sql . "<br>" . $conn->error;}
  }

 }

static public function insertp($nom ,$brand,$web_site,$code_product_site,$categorie,$stock,$monnaie,$prixssu,$url,$url_im)
{ $conn = new mysqli("localhost", "root", "", "webscrap");
$sql0='SELECT * 
FROM product p
INNER JOIN website w
ON p.web_site = w.name
INNER JOIN  brand b
ON p.brand = b.name
WHERE p.code_product_site= "'.$code_product_site.'"; ';  //check if the product already exists in the table product
$result = $conn->query($sql0);
$sql="";
if ($result->num_rows <= 0) 	{
	if($stock=="produit epuise")  //cas stock epuisé insertion pour la 1 ere fois 
	{ $sql='INSERT INTO `product`(`name`,`brand`,`web_site`,`code_product_site`, `category`,
	`disponibility`, `currency`, `price`, `last_scraping_date`, `date_end_sale`,
	`url_product`, `url_image1`, `url_image2`, `url_image3`, `url_image4`, `url_image5`)
	VALUES
	("'.$nom.'","'.$brand.'","'.$web_site.'","'.$code_product_site.'","'.$categorie.'","'.$stock.'","'.$monnaie.'","'.$prixssu.'",
	"'.date("Y-m-d h:i:sa").'","'.date("Y-m-d").'","'.$url.'","'.$url_im[0].'","'.$url_im[1].'",
         "'.$url_im[2].'","'.$url_im[3].'","'.$url_im[4].'")';	
		  if ($conn->query($sql) === TRUE) {echo "New record created successfully";}
	else {echo "Error: " . $sql . "<br>" . $conn->error;}
	echo products::insert(1,$code_product_site); }
	else {
		//cas  insertion pour la 1 ere fois (stock non epuisé)
	$sql='INSERT INTO `product`(`name`,`brand`,`web_site`,`code_product_site`, `category`,
	`disponibility`, `currency`, `price`, `last_scraping_date`, 
	`url_product`, `url_image1`, `url_image2`, `url_image3`, `url_image4`, `url_image5`)
		VALUES
		("'.$nom.'","'.$brand.'","'.$web_site.'","'.$code_product_site.'","'.$categorie.'","'.$stock.'","'.$monnaie.'","'.$prixssu.'",
		"'.date("Y-m-d h:i:sa").'","'.$url.'","'.$url_im[0].'","'.$url_im[1].'",
         "'.$url_im[2].'","'.$url_im[3].'","'.$url_im[4].'")';	
		  if ($conn->query($sql) === TRUE) {echo "New record created successfully";}
	else {echo "Error: " . $sql . "<br>" . $conn->error;}
	echo products::insert(0,$code_product_site);
	
	
}}
//if the product exists
else {
// cas sans changement de stock ni prix =>update last_scraping_date dans product et last_scraping_date et nb_no_changement dans history
while($row = $result->fetch_assoc()) {
 $sql='UPDATE `product` SET `last_scraping_date`="'.date("Y-m-d h:i:sa").'" where id_product="'.$row["id_product"].'" ';
 $conn->query($sql);
 if($row["disponibility"]==$stock && $row["price"]==$prixssu)
{ echo "*********************************************************************************************";
$sql0='UPDATE `history` SET `last_scraping_date`="'.date("Y-m-d h:i:sa").'" , `nb_no_changement`= `nb_no_changement`+1
WHERE id_product="'.$row["id_product"].'" 
ORDER BY `last_scraping_date` DESC LIMIT 1'; 
if ($conn->query($sql0) === TRUE) {echo "New record created successfully";}
	else {echo "Error: " . $sql . "<br>" . $conn->error;}
}
else
{	
echo products::insert(0,$code_product_site); //cas il existe un changement => new record
	}		
}


}
/**fin fonction***/
}
}
		
	
	


?>