<?php
// Inkludér WideImage-biblioteket
require 'includes/lib/WideImage.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billedeupload</title>
</head>

<body>
	<h2>Vælg en fil</h2>
    
    <?php
	// Formular til billedeupload
	// VIGTIGT: Husk enctype="multipart/form-data" i form-tagget, ellers kan formularen ikke uploade filen
	?>
    <form enctype="multipart/form-data" method="post">
    	<input type="file" name="fil">&nbsp;&nbsp;
        <input name="upload_fil" type="submit" value="Upload">
    </form>
    
    <?php
	// Hvis vi har sendt formular, køres følgende kodeblok
	if ( isset($_POST['upload_fil']) )
	{
		// Tjek indholdet af $_FILES-arrayet. Udkommentér følgende linje når alt virker.
		echo '<pre>Indholdet af $_FILES '; print_r($_FILES); echo '</pre>';
		
		// Hent oplysninger om den uploadede fil og gem dem i variablen $fil
		// VIGTIGT: 'fil' <-- Dette kommer fra input-feltets name-attribut.
		$fil = $_FILES['fil'];
		
		// Tjek om fil blev uploadet korrekt
		if ($fil['error'] == 0)
		{
			// Her tilføjer vi tidsstempel og _ til det eksisterende navn for at sikre det er unikt.
			$filnavn = time() . '_' . $fil['name'];
			
			// Tjek om det nye filnavn er genereret korrekt. Udkommentér følgende linje når alt virker.
			echo '<p>Nyt filnavn: ' . $filnavn . '</p>';
			
			// Load billede i WideImage vha. "name" på dit input-felt og gem object i variablen img
			$img = WideImage::load('fil');

			// Tilpas størrelsen, så billedet ikke er større end f.eks. FullHD
			$resized_img = $img -> resize(1920, 1080); // or hd: 1280, 720

			// Gem billedet i den ønskede mappe og filnavn
			$resized_img -> saveToFile('img/' . $filnavn);

			// Lav thumbnail med max-bredde på f.eks. 240 pixels (Højde beregnes automatisk for at bevare proportioner)
			$thumb = $img -> resizeDown(240);

			// Gem thumbnail i ønsket mappe
			$thumb -> saveToFile('img/thumbs/' . $filnavn);
			
			echo '<p>Filen blev uploadet!</p>';
			
			// Vis miniature
			echo '<img src="' . 'img/thumbs/' . $filnavn . '">';
			// Vis link til billedet i fuld størrelse
			echo '<p><a href="' . 'img/' . $filnavn . '" target="_blank">Se billedet i fuld størrelse</a></p>';
		}
		// Eller udskriv fejl
		else
		{
			echo '<p>Filen kunne ikke uploades!</p>';
		}
	}
	?>
</body>
</html>