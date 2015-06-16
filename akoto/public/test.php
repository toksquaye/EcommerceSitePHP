<?php
require_once("../includes/config.php");
$transId= '8w79fad';
$buyerName = 'toks';
$buyerShipAdd = 'afkkjlaf';
$buyerEmail = 'iewuari';
$ItemQtys = 'asfds';
$ItemNumbers = 'dasfjlads';
$ItemNames='jfkaslfj';
$ItemAmts='dsakjflaes';


$mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
					$mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
			$stmt = $mysqli->prepare("INSERT INTO transactions(TransID, PayerName, PayerAddress,PayerEmail, ItemName, ItemQty,ItemPrice, ProductNo)
								    VALUES(:TransID,:PayerName,:PayerAddress,:PayerEmail,:ItemName, :ItemQty,:ItemPrice,:ProductNo)"  );
					if (!$stmt) {
					    echo "\nPDO::errorInfo():\n";
					    print_r($mysqli->errorInfo());
					}

					if($stmt->execute(array(':TransID' => $transId,
								':PayerName' => $buyerName,
								':PayerAddress' => $buyerShipAdd,
								':PayerEmail' => $buyerEmail,
								':ItemName' => $ItemNames,
								':ItemQty' => $ItemQtys,
								':ItemPrice' => $ItemAmts,
								':ProductNo' => $ItemNumbers)))
					    echo "Query Success";
					    else {echo "Query Failed";
					    print_r($mysqli->errorInfo());}
?>