
<?php
var_dump($_FILES['picture']['name']);


	if(isset($_POST['watermarkPosition'])  && !empty($_FILES)){

			mkdir('watermarked/'.date('m_d_Y H_i_s', time()));

		for ($i=0; $i < count($_FILES['picture']['name']); $i++) {

			$imgWidth = getimagesize($_FILES['picture']['tmp_name'][$i])[0];
			$imgHeight = getimagesize($_FILES['picture']['tmp_name'][$i])[1];
			$watermarkWidth = getimagesize('watermark.png')[0];
			$watermarkHeight = getimagesize('watermark.png')[1];
			$watermarkSizeX = intval((($imgWidth*$imgHeight)/($watermarkWidth*$watermarkHeight)) * $watermarkWidth * $_POST['size']);
			$watermarkSizeY = intval((($imgWidth*$imgHeight)/($watermarkWidth*$watermarkHeight)) * $watermarkHeight * $_POST['size']);
			$margin = $_POST['margin'];
			$opacity = $_POST['opacity'];
			//$newImageName = $_POST['newName'];
			$newImageName = explode('.', $_FILES['picture']['name'][$i])[0];

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


			$pictureName = $_FILES['picture']['name'][$i];
			$pictureType = explode('/', $_FILES['picture']['type'][$i])[1];


		if($pictureType == 'png' || $pictureType == 'jpeg'){

			move_uploaded_file($_FILES['picture']['tmp_name'][$i], 'uploads/'.$pictureName);



			if($pictureType == 'png') {
				$image = imagecreatefrompng('uploads/'.$pictureName);
				$watermark = imagecreatetruecolor($watermarkSizeX,$watermarkSizeY);
				$watermarkTmp = imagecreatefrompng('watermark.png');
				imagecopyresampled($watermark, $watermarkTmp, 0,0,0,0,$watermarkSizeX,$watermarkSizeY,imagesx($watermarkTmp), imagesy($watermarkTmp));
				imagecopymerge($image, $watermark, $watermarkLocationX, $watermarkLocationY, 0, 0, $watermarkSizeX, $watermarkSizeY, $opacity);
				imagepng($image, 'watermarked/'.date('m_d_Y H_i_s a', time()).'/'.$newImageName.'.png');
			} elseif($pictureType == 'jpeg') {
				$image = imagecreatefromjpeg('uploads/'.$pictureName);
				$watermark = imagecreatetruecolor($watermarkSizeX,$watermarkSizeY);
				$watermarkTmp = imagecreatefrompng('watermark.png');
				imagecopyresampled($watermark, $watermarkTmp, 0,0,0,0,$watermarkSizeX,$watermarkSizeY,imagesx($watermarkTmp), imagesy($watermarkTmp));
				imagecopymerge($image, $watermark, $watermarkLocationX, $watermarkLocationY, 0, 0, $watermarkSizeX, $watermarkSizeY, $opacity);
				imagejpeg($image, 'watermarked/'.date('m_d_Y H_i_s', time()).'/'.$newImageName.'.jpeg');
			}
		} else {
			echo $pictureType;
			echo 'invalid picture type! only png and jpeg allowed';
		}
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
 	<form action="watermarkMultiple.php" method="POST" enctype="multipart/form-data" id="form">
 		<input type="file" name="picture[]" multiple><br>
 		NW<input type="radio" name="watermarkPosition" value="nw"><br>
 		N<input type="radio" name="watermarkPosition" value="n"><br>
 		NE<input type="radio" name="watermarkPosition" value="ne"><br>
 		E<input type="radio" name="watermarkPosition" value="e"><br>
 		SE<input type="radio" name="watermarkPosition" value="se" checked><br>
 		S<input type="radio" name="watermarkPosition" value="s"><br>
 		SW<input type="radio" name="watermarkPosition" value="sw"><br>
 		W<input type="radio" name="watermarkPosition" value="w"><br>
 		C<input type="radio" name="watermarkPosition" value="c"><br>

 		<!--Size: <input type="range" name="size" min="0" max="100" step="1"><br>-->

 		Size: <input type="number" id="size" name="size" min="0.1" max="1" step="0.1" value="0.2"><br>

 		<!--Margin: <input type="range" name="margin" min="0" max="100" step="1" value="10"><br>-->

 		Margin: <input type="number" id="margin" name="margin" min="0" max="1000" step="1" value="10"><br>

 		<!--Opacity: <input type="range" name="opacity" min="0" max="100" step="1" value="10"><br>-->

 		Opacity: <input type="number" name="opacity" min="0" max="100" step="1" value="15"><br>

 		New name: <input type="text" name="newName"><br>


 		<input type="submit" value="Submit">

 	</form>

	<canvas id="myCanvas" width="400" height="200" style="border:1px solid #000000;"></canvas>


 <br>
 <a href="watermarked"> Watermarked pictures</a>

 </body>
 </html>
