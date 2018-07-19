<meta http-equiv="Content-Type" content="text/html;
      charset=UTF-8" />

	  
	  <?php
	  //Recuperer toutes les marques du site darty.com dans la table Brands de la DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webscrap";
$connx = new mysqli($servername, $username, $password, $dbname);
class Brand{
            public $id;
            public $nom;
            
            
    
            public function __construct($id,$nom) {
              $this->nom = $nom;
               $this->id = $id;
			   
            }
            
}

$html = file_get_contents("https://www.darty.com/nav/achat/marque/index.html");  

$doc = new DOMDocument();
libxml_use_internal_errors(TRUE); 
if(!empty($html)){ 
echo "Les Marques du site Darty.com  <br>";
	$doc->loadHTML($html);
	libxml_clear_errors(); 
	$xpath = new DOMXPath($doc);
	$brands=$xpath->query('//div[@class="brand-index-detail"]/ul/li/a');
	$i=1;
	foreach ($brands as $brand) {
    echo $brand->nodeValue . "<br>";
	/*******************************INSERTION DANS LA BASE DE DONNEES*****************************************************************************************/
  $qry = $connx->prepare("INSERT INTO `brand`(`id`, `name`) VALUES (?,?);");
         
        $brand =new Brand($i,$brand->nodeValue);		 
        $qry->bind_param("ss",$brand->id,$brand->nom);
         $i=$i+1 ; 
        if (!$qry->execute()) {
    print_r($qry->error);
}	
	
	
}

	
	
	
}


 
   
	 
  
	
	 
	
	



?>