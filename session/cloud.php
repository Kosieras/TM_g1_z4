<?php 
//Spradzanie sesji
declare(strict_types=1);
session_start();

if (!isset($_SESSION['loggedin'])) //Jezeli nie ma sesji
{
  	header('Location: index3.php'); //Powrot do panelu logowania
	exit(); 
}
else{
  $connection = mysqli_connect('localhost', 'kosierap_z4', 'Laboratorium123', 'kosierap_z4');
  	if (!$connection){
		echo " MySQL Connection error." . PHP_EOL;
		echo "Errno: " . mysqli_connect_errno() . PHP_EOL; echo "Error: " . mysqli_connect_error() . PHP_EOL; exit;
	}
  ?>
    <!-- Prosty CSS -->
<html>
  <head>
<style>
  .tdFile{
   min-width:200px; 
  }
  .imgDel {
   max-width:25px; 
  }
  img {
    max-width:50px;
  }
  audio,video {
  width:100%;
    max-width:250px;
  }
  
input[type=submit] {
  background-color: white;
  color: black;
  border: 2px solid #555555;
}

input[type=submit]:hover {
  background-color: #555555;
  color: white;
}
.post{
  width: 50%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  border: 3px solid #ccc;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  outline: none;
}

.post:focus {
  border: 3px solid #555;
}
  .user, .post:disabled {
  width: 10%;
  color:red;
  padding: 12px 20px;
  margin: 8px 0;
  outline: none;
}
  
</style>  
  </head>
  <body>

<br> 
    <!--Przycisk do wylogowania sie -->
   <form action="logout.php"><br> <input type="submit" value="LOGOUT"/></form>
    <!--Dodawanie pliku-->
	<form name="formAdd" id="formAdd" method="POST" action="upload.php" enctype="multipart/form-data">
	Zalogowano jako:<input type="text" class="user" id="user" name="user" maxlength="10" size="10" value="<?php   echo $_SESSION['user'];?>" readonly><br>
<br>
	<label for="fileToUpload">
    	<img src="./icons/add.png"/>
    	<input type="file" name="fileToUpload" id="fileToUpload" style="display:none">
  	</label>
	</form>
    <!--Powrót do folderu macierzystego -->
    <form method="POST" action="cloud.php" enctype="multipart/form-data">
  	<label id="labelBack" for="back">
    	<img src="./icons/return.png"/>
    	<input type="submit" name="back" id="back" style="display:none">
  	</label>
    </form>
    <!--Dodawanie nowego folderu-->
   <label id="labelAdd" for="addFolder">
     <img src="./icons/addFolder.png"/>
     <input type="checkbox" id="addFolder" name="addFolder" value="value1" style="display:none">
  </label>
	<div id="up" style="visiblity:hidden; position:absolute;">
	<form method="POST" action="cloud.php" enctype="multipart/form-data">
    <input type="text" name="folderName" id="folderName" value="Folder">
    <input type="submit" id="createFolder" name="createFolder" value="Add"/>      
  </form>
   </div> 
  <br>
<br>
  <br>
    <label for="refresh">
     <img src="./icons/refresh.png"/>
    <button id="refresh" name="refresh" onClick="window.location.reload();"></button>
      </label>
  <br>
   
<script>
  	//Zmienne JS
	var dir = "<?php echo $_GET['dir']; ?>";
  	var labelAdd = document.getElementById("labelAdd");
  	var labelBack = document.getElementById("labelBack");
    var checkbox = document.getElementById('addFolder');
	var delivery_div = document.getElementById('up');
 
  	//Wyswietlanie opcji do wpisania nazwy folderu
 document.addEventListener("DOMContentLoaded", checkbox.onclick =function(event) {
   if(this.checked) {
     delivery_div.style['visibility'] = 'visible';
   } else {
     delivery_div.style['visibility'] = 'hidden';
   }
});
   	//Automatyczne zatwierdzanie po wybraniu pliku do wyslania
 	document.getElementById("fileToUpload").onchange = function() {
    document.getElementById("formAdd").submit();
    };
   	//Wyswietlanie lub nie odpwowiednich przyciskow
   if(dir) labelAdd.setAttribute("hidden", "hidden");
	else labelAdd.removeAttribute("hidden"); 
  if(!dir) labelBack.setAttribute("hidden", "hidden");
	else labelBack.removeAttribute("hidden"); 
    </script>
<?php
    //Zmienne
    $userSession = $_SESSION['user'];
	$createFolder = $_POST['createFolder'];
	$deleteFile = $_GET['delete'];
	$folderName = $_POST['folderName'];
  	//Funkcjonalnosc przycisku do tworzenia nowego folderu
     if(isset($createFolder)){
		 mkdir("../users/$userSession/$folderName", 0755, true);
	}
	//Tabela z plikami
	print "<TABLE CELLPADDING=5 BORDER=1>";
	print "<TR><TD></TD><TD>FILE</TD></TR>\n"; 
	$dir = "../users/".$userSession."/".$_GET['dir'];
    mkdir("../users/$userSession", 0755, true);
	$files = array_diff(scandir($dir), array('.', '..'));
  	foreach ($files as $value) {
    //Sprawdza typ plikow, aby w odpowiedni sposob je wyswietlic
    if(is_dir("../users/$userSession/$value")) print "<TR><TD> <img src='./icons/folder.png'/></TD><TD> <a href='cloud.php?dir=$value'>$value</a>";
    else if(end(explode(".",$value)) =="png" || end(explode(".",$value)) =="jpg" || end(explode(".",$value)) =="jpeg" || end(explode(".",$value)) =="gif") print "<TR><TD><img src='$dir/$value'/></TD><TD><a href='$dir/$value' download>$value</a>";
    else if(end(explode(".",$value)) =="mp3") print "<TR><TD> <audio controls src='$dir/$value'><a href='$dir/$value'>Download audio</a></audio></TD><TD><a href='$dir/$value' download>$value</a>";
    else if(end(explode(".",$value)) =="mp4") print "<TR><TD> <video controls src='$dir/$value'></video></TD><TD><a href='$dir/$value' download>$value</a>";
    else print "<TR><TD><img src='./icons/file.png'/></TD><TD><a href='$dir/$value' download>$value</a>";
     //Ikona usuwania pliku/folderu
    print "<label for='$value'>
	<form method='POST' action='cloud.php?delete=$value' enctype='multipart/form-data'>
	<img id='imgDel' class='imgDel' src='./icons/delete.png'>
    <input type='submit' id='$value' name='$value' value='Delete' style='display:none'>
    </form></label></TD></TR>\n";   
}
	print "</TABLE>"; 
  	//Funkcjonalnosc usuwania pliku
	if(isset($deleteFile)){
  		$deleteThis = $_SESSION['dirSession']."/".$deleteFile;
		system("rm -rf ".escapeshellarg("$deleteThis"));
    	header ('Location: cloud.php');
  		exit();
}
  	$_SESSION['dirSession'] = "../users/".$userSession."/".$_GET['dir'];
  	mysqli_close($connection);
}
?>
  </body>
</html>
