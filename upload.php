<?php 
 
// be aware of file / directory permissions on your server 
$file = md5(time()).rand(383,1000);
$filename = 'uploads/webcam/'.$file.'.jpg';
if(move_uploaded_file($_FILES['webcam']['tmp_name'], $filename))
 echo "Success";
else
 echo "Unsuccess"; 
 
define( 'API_BASE_URL',     'https://api.projectoxford.ai/face/v1.0/detect?' );
define( 'API_PRIMARY_KEY',      'b75fc2d1cf1c4665ab9123f670933ceb' );
$img = 'http://rishabhjain53.esy.es/frontend/'.$filename;

$post_string = '{"url":"' . $img . '"}';

$query_params = array(
  'returnFaceId' => 'true',
    'returnFaceLandmarks' => 'true',
	  'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses',
);

$params = '';
foreach( $query_params as $key => $value ) {
    $params .= $key . '=' . $value . '&';
}
$params .= 'subscription-key=' . API_PRIMARY_KEY;

$post_url = API_BASE_URL . $params;

$ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($post_string))
    );    

    curl_setopt( $ch, CURLOPT_URL, $post_url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_string );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $response1 = curl_exec( $ch );
curl_close( $ch );
define( 'API_BASE_URL1',     'https://api.projectoxford.ai/emotion/v1.0/recognize' );
define( 'API_PRIMARY_KEY1',      '7df61181221f4d1cabbac3e60d097d3a' );
$img = 'http://aasimk.esy.es/frontend/'.$filename;

$post_string = '{"url":"' . $img . '"}';

$query_params = array();

$params = '';
foreach( $query_params as $key => $value ) {
    $params .= $key . '=' . $value . '&';
}
$params .= 'subscription-key=' . API_PRIMARY_KEY1;

$post_url = API_BASE_URL1 . $params;

$ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($post_string))
    );    

    curl_setopt( $ch, CURLOPT_URL, $post_url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_string );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $response = curl_exec( $ch );
curl_close( $ch );
print_r( $response1);


$serres = json_decode($response1);



print_r($serres[0]->faceAttributes->gender);
print_r($serres[0]->faceAttributes->age);

$gen = $serres[0]->faceAttributes->gender;
$age = $serres[0]->faceAttributes->age;

	include 'include/db_connect.php';
if($age!=0)
{
    $sql = "INSERT into person(age,gender) VALUES('".$age."','".$gen."')";
			
			
			if(mysqli_query($conn,$sql)){
				//echo "<br/>Inserted";
			}
			else              //error!
			{
				echo "<html><head><link href = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css' rel = 'stylesheet'><link rel='stylesheet' href='css/style.css'></head><body>";
				include 'include/header.php';
				echo "<div class='container'><div class='row'><hr class='col-md-12'></div></div>";
				echo "<div style='text-align:center'>";
				echo "<br><h3>Could not register!,please fill details and register again!</h3><br>";
				echo "</div></body></html>";
			}
}			
//echo "Gender: ".$serres[0]->gender;
unlink($filename);
?>