<?php 

	if(isset($_POST['watermarkPosition'])  && !empty($_FILES)){


			$imgWidth = getimagesize($_FILES['picture']['tmp_name'])[0];
			$imgHeight = getimagesize($_FILES['picture']['tmp_name'])[1];
			$watermarkWidth = getimagesize('watermark.png')[0];
			$watermarkHeight = getimagesize('watermark.png')[1];
			$watermarkSizeX = intval(($_POST['size']*($watermarkWidth/100))*($imgWidth/$watermarkWidth));
			$watermarkSizeY = intval(($_POST['size']*($watermarkHeight/100))*($imgHeight/$watermarkHeight));
			$margin = $_POST['margin'];
			$opacity = $_POST['opacity'];
			$newImageName = $_POST['newName'];

			switch($_POST['watermarkPosition']) {
				case 'nw':
					$watermarkLocationX = 0+$margin;
					$watermarkLocationY = 0+$margin;
					break;
				case 'n':
					$watermarkLocationX = $imgWidth/2-$watermarkSizeX/2;
					$watermarkLocationY = 0+$margin;
					break;
				case 'ne':
					$watermarkLocationX = $imgWidth-$watermarkSizeX-$margin;
					$watermarkLocationY = 0+$margin;
					break;
				case 'e':
					$watermarkLocationX = $imgWidth-$watermarkSizeX-$margin;
					$watermarkLocationY = $imgHeight/2-$watermarkSizeY/2;
					break;
				case 'se':
					$watermarkLocationX = $imgWidth-$watermarkSizeX-$margin;
					$watermarkLocationY = $imgHeight-$watermarkSizeY-$margin;
					break;
				case 's':
					$watermarkLocationX = $imgWidth/2-$watermarkSizeX/2;
					$watermarkLocationY = $imgHeight-$watermarkSizeY-$margin;
					break;
				case 'sw':
					$watermarkLocationX = 0+$margin;
					$watermarkLocationY = $imgHeight-$watermarkSizeY-$margin;
					break;
				case 'w':
					$watermarkLocationX = 0+$margin;
					$watermarkLocationY = $imgHeight/2-$watermarkSizeY/2;
					break;
				case 'c':
					$watermarkLocationX = $imgWidth/2-$watermarkSizeX/2;
					$watermarkLocationY = $imgHeight/2-$watermarkSizeY/2;
					break;
			}
			
		
			$pictureName = $_FILES['picture']['name'];
			$pictureType = explode('/', $_FILES['picture']['type'])[1];
			



		if($pictureType == 'png' || $pictureType == 'jpeg'){
			
			move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/'.$pictureName);
			
			if($pictureType == 'png') {
				$image = imagecreatefrompng('uploads/'.$pictureName);
				$watermark = imagecreatetruecolor($watermarkSizeX,$watermarkSizeY);
				$watermarkTmp = imagecreatefrompng('watermark.png');
				imagecopyresampled($watermark, $watermarkTmp, 0,0,0,0,$watermarkSizeX,$watermarkSizeY,imagesx($watermarkTmp), imagesy($watermarkTmp));
				imagecopymerge($image, $watermark, $watermarkLocationX, $watermarkLocationY, 0, 0, $watermarkSizeX, $watermarkSizeY, $opacity);
				imagepng($image, 'watermarked/'.$newImageName.'.png');
			} elseif($pictureType == 'jpeg') {
				$image = imagecreatefromjpeg('uploads/'.$pictureName);
				$watermark = imagecreatetruecolor($watermarkSizeX,$watermarkSizeY);
				$watermarkTmp = imagecreatefrompng('watermark.png');
				imagecopyresampled($watermark, $watermarkTmp, 0,0,0,0,$watermarkSizeX,$watermarkSizeY,imagesx($watermarkTmp), imagesy($watermarkTmp));
				imagecopymerge($image, $watermark, $watermarkLocationX, $watermarkLocationY, 0, 0, $watermarkSizeX, $watermarkSizeY, $opacity);
				imagejpeg($image, 'watermarked/'.$newImageName.'.jpeg');
			}



		} else {
			echo $pictureType;
			die('invalid picture type! only png and jpeg allowed');
		}


	} else {
		echo 'Select all parameters!';
	}

 ?>

 <html>
 <head>
 	<title>Watermark</title>
 </head>
 <body>
 	<form action="watermarkTest.php" method="POST" enctype="multipart/form-data">
 		<input type="file" name="picture"><br>
 		NW<input type="radio" name="watermarkPosition" value="nw"><br>
 		N<input type="radio" name="watermarkPosition" value="n"><br>
 		NE<input type="radio" name="watermarkPosition" value="ne"><br>
 		E<input type="radio" name="watermarkPosition" value="e"><br>
 		SE<input type="radio" name="watermarkPosition" value="se"><br>
 		S<input type="radio" name="watermarkPosition" value="s"><br>
 		SW<input type="radio" name="watermarkPosition" value="sw"><br>
 		W<input type="radio" name="watermarkPosition" value="w"><br>
 		C<input type="radio" name="watermarkPosition" value="c"><br>

 		<!--Size: <input type="range" name="size" min="0" max="100" step="1"><br>-->

 		Size: <input type="number" name="size" min="0" max="100" step="1" value="25"><br>

 		<!--Margin: <input type="range" name="margin" min="0" max="100" step="1" value="10"><br>-->

 		Margin: <input type="number" name="margin" min="0" max="100" step="1" value="10"><br>

 		<!--Opacity: <input type="range" name="opacity" min="0" max="100" step="1" value="10"><br>-->

 		Opacity: <input type="number" name="opacity" min="0" max="100" step="1" value="4"><br>

 		New name: <input type="text" name="newName"><br>
 		
 		
 		<input type="submit" value="Submit">

 	</form>
 <br>
 <a href="watermarked"> Watermarked pictures</a>
 </body>
 </html>