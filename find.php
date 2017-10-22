<?php
// List of near by hospitals is saved in $hospital_list variable and the number of hospitals in the list can be manipulated by changing values of $count near line 120
 require_once('connect.php');
 //function for obtaining latitude and longitude
 function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     try{
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
     }catch(Exception $e){
      return;
     }
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}

// Class for priority queue
 class PQtest extends SplPriorityQueue
{
    public function compare($priority1, $priority2)
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 < $priority2 ? 1 : -1;
    }
}

$objPQ = new PQtest();
/*For testing:
$objPQ->insert('A',3);
$objPQ->insert('B',6);
$objPQ->insert('C',1);
$objPQ->insert('D',2);

//print_r($objPQ->extract() );

echo "COUNT->".$objPQ->count()."<BR>";

//mode of extraction
$objPQ->setExtractFlags(PQtest::EXTR_BOTH);

//Go to TOP
$objPQ->top();

while($objPQ->valid()){
    print_r($objPQ->current());
    echo "<BR>";
    $objPQ->next();
} 

*/

 if (!isset($_COOKIE['hospitalid']))
	{ // If cookie is not set redirect to login page
		header("Location: login.php");
	}
  else{

    $hid= $_COOKIE['hospitalid']; // Extracting value of hospital id from set cookie
     $result = mysqli_query($connection, "SELECT id,hospital,address,latitude,longitude FROM registration where id='$hid' ");
     $user= mysqli_fetch_assoc($result);

  }
  $display =0; // For displaying tables
if(isset($_POST['blood_type']) && isset($_POST['address'])  && isset($_POST['city']) && isset($_POST['quantity'])){

 $count=5;
  $address= htmlentities($_POST['address'] );
 	$blood_type=  htmlentities($_POST['blood_type']);
  $city=  htmlentities($_POST['city'] );
  $quantity=  htmlentities($_POST['quantity'] );
  
  if(!empty($address)&& !empty($blood_type)&& !empty($city)  && !empty($quantity)){
    try{
      $loc= geocode($address);
      $latitude=$loc[0];
      $longitude=$loc[1];
      $address_found=1;
    }catch(Exception $e){
      $address_found=0;
    }

    if($address_found){ 

 	    $query = "(SELECT id,hospital,address,latitude,longitude FROM registration where registration.city ='$city' and registration.id in
         (SELECT hospital_database.h_id FROM hospital_database where hd_bloodtype='$blood_type' and hd_bloodunits>=$quantity  ) ORDER BY SQRT( POWER($latitude-registration.latitude,2)+ POWER($longitude-registration.longitude,2) ) LIMIT $count )
         UNION 
         (SELECT id,hospital,address,latitude,longitude FROM registration JOIN hospital_database on hospital_database.h_id=registration.id 
          where registration.city ='$city' and hospital_database.hd_bloodtype='$blood_type' and hospital_database.hd_bloodunits>=$quantity ORDER BY hd_expiry LIMIT $count )";
      
 	    $result = mysqli_query($connection, $query);
      
      if($result ){
       
        $display=1; // For displaying tables
        $hospital_list= array();
  	     while($data= mysqli_fetch_assoc($result)){

            
          array_push( $hospital_list, $data);
         
        }
        $json=json_encode($hospital_list);
 	    }else{
 		     echo "Search Failed! No Data Found.";
 	    }
    }else{
      echo "Search Failed! Invalid Address, try including city name.";
    }
  }
}
 ?>

<!DOCTYPE html>
<html>
<head>
  <title>LIFE-LINE:Hospital Record</title>

	
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LIFE-LINE Login Form</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">
		<link href="css/agency.min.css" rel="stylesheet">
		<link href="css/modern-business.css" rel="stylesheet">
		<link href="css/agency.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/11.png">
	
		<style>
		table{
			
			border:double;
			border-width:6px;
		}
		body{
			background-color: lightgray;
		
			
		}
		label{
			
			color:black;
		}
		h3{
			color:black;
			
			
		}
            form { border: dotted;
             margin: 0 auto; 
            width:95%;
                align-self: auto;
                border-top-width: medium;
                padding-top: 20px;
                padding-bottom: 65px;
}
		
		</style>
		
</head>
<body>
<div>
<?php require_once 'navbar2.php'; ?>
</div>
    <div id="detailform">
  <form method="POST" style="margin-top:100px" border="1">
  <label for="address">Address:</label>
  <textarea name="address" rows=5 cols=25 > <?php print_r($user['address']); ?> </textarea><br><br>
   <label for="city">Searching Blood in City:</label> 
  <select name="city" id="city" >
                      <option value="Mumbai">Mumbai</option>
                      <option value="Pune">Pune</option>
					  <option value="Delhi">Delhi</option>
					  <option value="Chennai">Chennai</option>
					  <option value="Banglore">Banglore</option>
					  
  </select> 
   <br><br>
  <label for="expiry">Enter required blood group:</label>          
  <select   name="blood_type" id="btype" >
    
                      <option value="A+">A+</option>
                      <option value="A-">A-</option>
                      <option value="B+">B+</option>
                      <option value="B-">B-</option>
                       <option value="AB+">AB+</option>
                      <option value="AB-">AB-</option>
                      <option value="O+">O+</option>
                      <option value="O-">O-</option>
   </select> 
   <br><br>
    <label for="quantity">Quantity:</label>
  <input type="number" name="quantity" value="1"\><br><br>
      
                       <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Search</button>
                            </div>
                    
  <!-- <input type="submit" value="Search"><br>-->
      
    </form></div>
    <h3><i>Nearby Hospitals where required blood group is available:</i></h3>
    <center><table >
       <?php
       if(isset($_POST['blood_type']) && isset($_POST['address']) && $display && count($hospital_list)>0){
      echo "<tr>
              <th >Hospital Name</th>
              <th >Address</th>
            </tr>";
     
        
        foreach ($hospital_list as $row) {
          $key = $row['id'];
          $url= "display.php?key=".$key;
          echo '<tr>',
              '<td><a href="'.$url.'">', $row['hospital'] ,'</a></td>',
              '<td>' , $row['address'] , '</td>',
            '</tr>';
        }
      }
      ?>
        </table></center><br><br> 
     <h3>Google Maps</h3>
    <div id="map" style="width:100%; height:100%;"></div>

      
   
	<footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright"><font color="black">Copyright &copy; ZeroHour 2017</font></span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
						
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#"><font color="black">Privacy Policy</font></a>
                        </li>
                        <li><a href="#"><font color="black">Terms of Use</font></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

     <script>
    function initialize(){
  createMap();
}
   
    function createMap(){
     var json_array=JSON.parse('<?php echo json_encode($hospital_list);?>');
  var responce=JSON.stringify(json_array);
        console.log(responce);
//        var myObject=JSON.parse(responce);
        var jsonData=JSON.parse(responce)
    var map = initMap();
        for(var i in jsonData){
            console.log(jsonData[i].latitude);
            console.log(jsonData[i].longitude);
            var title="Hospital: "+jsonData[i].hospital+"\nAddress: "+jsonData[i].address;
            addMarker(jsonData[i].latitude, jsonData[i].longitude, map ,title)
            }
        }
        
        function addMarker(latmap,lngmap, gmap , gtitle){
            var uluru = new google.maps.LatLng(latmap, lngmap);

            //{lat: latmap, lng: lngmap};
             var marker = new google.maps.Marker({
                position: uluru,
                map: gmap,
                title: gtitle
    });
        }
        
    function initMap() {
        var uluru = {lat: 19.0760, lng: 72.8777};
         map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: uluru
        });
      return map;
      }
        
    </script>
      
      <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCy2iHGtgTZyXrZvCsAbqFq4qR8Y-5FnZ8&callback=initialize">
    </script>

	 
</body>
</html>