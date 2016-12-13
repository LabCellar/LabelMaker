<?php
/*
   SnipeIT label generator
   LabCellar, 2016
*/

require('assetlabel.php');
require('config.php');

if (!empty($_POST['assetnumber'])) {
    $database = new PDO(DATABASE_DSN, DATABASE_USERNAME, DATABASE_PASSWORD);
    $query = $database->prepare('SELECT * FROM assets WHERE asset_tag = :asset_tag');
    $stmt = $query->execute(
	array(
	    ":asset_tag" => $_POST['assetnumber']
	)
    );

    if (!$stmt) {
	exit("Erreur lors de la requête SQL");
    }
    
    $result = $query->fetch();
    
    $pdf = new AssetLabel(120,30);

    // Logo
    $pdf->Rotate(90);
    $pdf->Image('logo.jpg',-30,2,30);
    $pdf->Rotate(0);

    // Asset infos
    $pdf->SetFont('Arial','',12);
    $pdf->SetY(2);
    $pdf->SetX(13);
    $pdf->Cell(80,5,$result['name'],0,1);
    $pdf->SetFont('Arial','',7);
    $pdf->SetX(13);
    $pdf->Cell(20,4,'Serial : '.$result['serial'],0,1);
    $pdf->SetX(13);
    $pdf->Cell(20,4,'Asset number : '.$_POST['assetnumber']);

    // Barcode
    $pdf->Rotate(90);
    $pdf->Code39(16, 86, $_POST['assetnumber'], 0.55, 10);

    $pdf->Output();
} else {

?>
    <html>
	<head>
	    <title>Générateur d'étiquettes Snipeit</title>
	    <meta charset="utf-8">
	</head>
	<body>
	    <form method="POST">
		Tapez votre numéro d'asset : <input type="text" name="assetnumber"> <input type="submit" value="Générer une étiquette">
	    </form>
	</body>
    </html>
<?php

}

?>
