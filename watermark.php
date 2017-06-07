
<?php

	if(isset($_POST['watermarkPosition'])  && !empty($_FILES)){


			$imgWidth = getimagesize($_FILES['picture']['tmp_name'])[0];
			$imgHeight = getimagesize($_FILES['picture']['tmp_name'])[1];
			$watermarkWidth = getimagesize('watermark.png')[0];
			$watermarkHeight = getimagesize('watermark.png')[1];
			$watermarkSizeX = intval((($imgWidth*$imgHeight)/($watermarkWidth*$watermarkHeight)) * $watermarkWidth * $_POST['size']);
			$watermarkSizeY = intval((($imgWidth*$imgHeight)/($watermarkWidth*$watermarkHeight)) * $watermarkHeight * $_POST['size']);
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


<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>


 	<title>Watermark</title>
 </head>
 <body>
 	<form action="watermark.php" method="POST" enctype="multipart/form-data" id="form">
 		<input type="file" name="picture" id="file"><br>
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

 <script type="text/javascript">

	var imgWidth = 0;
	var imgHeight = 0;
	var canvas = document.getElementById("myCanvas");
	var canvasContext = canvas.getContext("2d");

 $(document).ready(function(){
	 var _URL = window.URL || window.webkitURL;

	 $("#file").change(function(e) {

		var image, file;

		if ((file = this.files[0])) {

				image = new Image();

				image.onload = function() {
						imgWidth = this.width;
						imgHeight = this.height;
						canvas.width = imgWidth*0.3;
						canvas.height = imgHeight*0.3;
				};
				image.src = _URL.createObjectURL(file);
		}
	 });
 });

 $('form :input').change(function(r) {
	 var watermarkLocationX = 0;
	 var watermarkLocationY = 0;
	 var margin = document.getElementById("margin").value*0.3;
	 var size = document.getElementById("size").value;

	 var watermarkSizeX = ((canvas.width*canvas.height)/(997*0.3*1000*0.3))* 0.3 * 997 * size;
	 var watermarkSizeY = ((canvas.width*canvas.height)/(997*0.3*1000*0.3))* 0.3 * 1000 * size;

	switch(document.querySelector('input[name="watermarkPosition"]:checked').value)
	{
		case 'nw':
			watermarkLocationX = 0+margin;
			watermarkLocationY = 0+margin;
			break;
		case 'n':
			watermarkLocationX = canvas.width/2-watermarkSizeX/2;
			watermarkLocationY = 0+margin;
			break;
		case 'ne':
			watermarkLocationX = canvas.width-watermarkSizeX-margin;
			watermarkLocationY = 0+margin;
			break;
		case 'e':
			watermarkLocationX = canvas.width-watermarkSizeX-margin;
			watermarkLocationY = canvas.height/2-watermarkSizeY/2;
			break;
		case 'se':
			watermarkLocationX = canvas.width-watermarkSizeX-margin;
			watermarkLocationY = canvas.height-watermarkSizeY-margin;
			break;
		case 's':
			watermarkLocationX = canvas.width/2-watermarkSizeX/2;
			watermarkLocationY = canvas.height-watermarkSizeY-margin;
			break;
		case 'sw':
			watermarkLocationX = 0+margin;
			watermarkLocationY = canva.hHeight-watermarkSizeY-margin;
			break;
		case 'w':
			watermarkLocationX = 0+margin;
			watermarkLocationY = canvas.height/2-watermarkSizeY/2;
			break;
		case 'c':
			watermarkLocationX = canvas.width/2-watermarkSizeX/2;
			watermarkLocationY = canvas.height/2-watermarkSizeY/2;
			break;
	}
		console.log(watermarkLocationX);
		console.log(watermarkLocationY);
		console.log(watermarkSizeX);
		console.log(watermarkSizeY);
		canvasContext.clearRect(0, 0, canvas.width, canvas.height);
	 canvasContext.fillRect(watermarkLocationX, watermarkLocationY, watermarkSizeX, watermarkSizeY);

 });

 </script>


 </body>
 </html>
