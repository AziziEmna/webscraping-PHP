<?php 
//faire la mise a jour de la table history
//s'il existe un changement de prix ou stock => modification du prix ou stock dans l'enregistrement et
// l'incrementation du nombre de changement (+1) et mise a jour de la date du dernier scrapping

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webscrap";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM history";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
		
	$htmld = file_get_contents($row["url produit"]);
    $docd = new DOMDocument();
	libxml_use_internal_errors(TRUE); 
	libxml_clear_errors(); 
	$docd->loadHTML($htmld);
	$xpathd = new DOMXPath($docd); 
    $stock = $xpathd->query('//meta[@name="product_stock"]/@content')->item(0)->nodeValue; //disponibilite du produit
	 $search = array("\t", "\n", "\r");
     $stock = str_replace($search, '', $stock);
	$prix= $xpathd->query('//span[@class="darty_prix darty_mediumbig"]')->item(0)->nodeValue; //prix
    $prixsu= preg_replace('/[^0-9,]/', '', $prix);	
	 $prixsu=  str_replace(',', '.', $prixsu);
	$prixssu=(float)$prixsu; //prix ss la monnaie
	if ($row["price"]!=$prixssu)
	{if($row["stock"]!=$stock)
		{$nb=(int)$row["nb_no_changement"]+1;
			$sql = "UPDATE `history` SET 
			`last_scraping_date`='".date('Y-m-d')."',`price`='".$prixssu."',`stock`='".$stock."',
			`nb_no_changement`='".$nb."' WHERE id_product='".$row["id_product"]."' ;" ;
			if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
		}
     $nb=(int)$row["nb_no_changement"]+1;
			$sql = "UPDATE `history` SET 
			`last_scraping_date`='".date('Y-m-d')."',`price`='".$prixssu."',`stock`='".$stock."',
			`nb_no_changement`='".$nb."' WHERE id_product='".$row["id_product"]."' ;" ; //mise à jour d'enregistrement 
			if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
    }		
	

	
		
       
    }
} else {
    echo "0 results";
}
$conn->close();




?>