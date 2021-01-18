<?php
if(isset($_GET["page"])){
	$page = $_GET["page"];
}
else {
	$page = 1;
}

$url = 'http://localhost:8983/solr/start/selectCheck?debug.explain.structured=true&debugQuery=on&df=attr_text&hl.fl=attr_text&hl=true&q="merida"OR"yucaten" ';
echo($url);
$file = file_get_contents($url);
print_r($file);
eval("\$result = " . $file . ";");
$numOfPages = ceil($result["response"]["numFound"]/10);



for($i=0; $i<count($result["response"]["docs"]) ; $i++){
	echo "=========Result ".($i+$start+1)."=========<br>";
	foreach($result["response"]["docs"][$i] as $k=>$v){
		display($k,$v);
		echo "<br>";
	}
	echo "<br>";
}

function display($k,$x){
	if($k=="_version_"){
		return;
	}
	
	echo $k.": ";
	
	if(!is_array($x)){
		echo $x;
		return;
	}
	
	for($i=0; $i<count($x); $i++){
		echo $x[$i]."-";
	}
}

for($i=0; $i<$numOfPages; $i++){
	echo "<a href='index.php?page=".($i+1)."'>[".($i+1)."]</a> ";
}
?>