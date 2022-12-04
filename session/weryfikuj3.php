<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
  <HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  </HEAD>
<BODY>
<?php
$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); 
$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8");
$link = mysqli_connect(localhost, kosierap_z4, Laboratorium123, kosierap_z4);
if(!$link) { echo"Error: ". mysqli_connect_errno()." ".mysqli_connect_error(); }
mysqli_query($link, "SET NAMES 'utf8'");
$result = mysqli_query($link, "SELECT * FROM users WHERE (username='$user')");
$rekord = mysqli_fetch_array($result); 
if(!$rekord) {
$ipaddress = $_SERVER["REMOTE_ADDR"];
function ip_details($ip) {
$json = file_get_contents ("http://ip-api.com/json/{$ip}"); $details = json_decode ($json);
return $details;
}

function ip_details($ip) {
$json = file_get_contents ("http://ipinfo.io/{$ip}/geo"); $details = json_decode ($json);
return $details;
}
//LABORATORIUM 2 - z index10.php
$ipaddress = $_SERVER["REMOTE_ADDR"];
//Logowanie do bazy
$link2 = mysqli_connect(localhost, kosierap_z4, Laboratorium123, kosierap_z4);
if(!$link2) { 
  echo"Error: ". mysqli_connect_errno()." ".mysqli_connect_error(); 
}
mysqli_query($link2, "SET NAMES 'utf8'");
	//Umieszczenie w bazie rekordu z 2 kolumnami
     $sql = "INSERT INTO goscieportalu (ipaddress, datetime) VALUES ('$ipaddress', CURRENT_TIMESTAMP);"; //Komenda MySQL do dodawania uzytkownika
    if (mysqli_query($link2, $sql)) {
      echo "Zapisano adres IP!";
} 
else {
   echo "Error: " . $sql . "<br>" . mysqli_error($link2); //Wyswietl blad z MySQL
}
  
//KONTYNUACJA Z LABORATORIUM 1
mysqli_close($link);
echo "Nie ma takiego użytkownika!";
 }
else {
  $klucz_apcu = "{$_SERVER['SERVER_NAME']}~login:{$_SERVER['REMOTE_ADDR']}"; //Tworzenie klucza APCu
  $i = (int)apcu_fetch($klucz_apcu); //Licznik do klucza APCu
  if ($i >= 2) { //Jezeli bedzie wiecej niz 2 proba logowania
    echo "Przekroczono liczbę prób logowania dla twojego IP({$_SERVER['REMOTE_ADDR']}) - zablokowany dostęp przez minutę"; //Prosty komunikat
    $ipaddress2 = $_SERVER["REMOTE_ADDR"];
    $link3 = mysqli_connect(localhost, kosierap_z4, Laboratorium123, kosierap_z4);
    $break = "INSERT INTO break_ins (ipaddress) VALUES ('$ipaddress2');"; //Komenda MySQL do dodawania uzytkownika
    if (mysqli_query($link3, $break)) {
      echo "Zapisano break!";
} 
else {
   echo "Error: " . $break . "<br>" . mysqli_error($link2); //Wyswietl blad z MySQL
}
    exit();
  }
  if($rekord['password']==$pass){ //Jezeli haslo jest prawidlowe
    session_start(); //Rozpoczecie sesji
    $_SESSION ['user'] = $user;
	$_SESSION ['loggedin'] = true;
	echo "Logowanie Ok. User: {$rekord['username']}. Hasło: {$rekord['password']}";
  	apcu_delete($klucz_apcu); //Usuwa klucz APCu przez co bedzie mozliwe ponowne logowanie
    header('Location: index4.php'); //Przejdz dalej do index4.php
 }
  else{
    echo "Błędne hasło";
    
   	apcu_inc($klucz_apcu, 1, $fail, 60); //Dodaj do licznika przez 60s
    mysqli_close($link);    
  }
 }
?> </BODY> </html>
