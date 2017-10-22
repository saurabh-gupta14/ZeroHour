<?php

// Use one of the following urls for the required json response

// For general= http://localhost/lifelinefinal/response.php?key=find&&value={"id":"17","hospital":"Kalsekar","address":" Dawle Village, Kausa Mumbra, Opposite Kalsekar Degree College, Near Bharat Gear, Thane, Maharashtra 400612","latitude":"19.1584","longitude":"73.0294","bloodtype":"1","city":"Mumbai","quantity":"1","expiry":"2017-12-12","blooduid":"111"}

header("Content-type:application/json");

$connection = mysqli_connect('localhost', 'root', '');
if(!$connection){
  die("Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'hospital');
if(!$select_db){
  die("Selection Failed" . mysqli_error($connection));
}

$arr =  array(1 =>  "A+",
                2 =>  "A-",
                3 =>  "B+",
                4 =>  "B-",
                5 =>  "AB+",
                6 =>  "AB-",
                7 =>"O+" ,
                8 => "O-");
 $arr1 =  array("A+"=> 1,
                      "A-"=> 2,
                      "B+"=> 3,
                      "B-"=> 4,
                      "AB+"=> 5,
                      "AB-"=> 6,
                      "O+"=> 7,
                      "O-"=> 8 );
 
if(isset($_GET['key'])&&isset($_GET['value'])){

  $json_array=json_decode($_GET['value'],true);
  $func= $_GET['key'];

switch($func){

  case "login":
    $json_string = login();
    break;


  case "delete":
    $json_string = delete();
    break;


  case "check":
    $json_string = check();
    break;

  case "insert":
    $json_string = insert();
    break;

  case "find":
    $json_string = view_hospitals();
    break;
  default:
    $json_string= null;
    break;
}

echo $json_string;
}


// For check= http://localhost/lifelinefinal/response.php?key=check&&value={"id":"17","blooduid":"111"}
function check(){
  global $json_array, $connection, $arr, $arr1;

  
  $blood_uid=  $json_array['blooduid'] ;

  if (!empty($blood_uid)) {
        $hid= $json_array['id'];
        $hospital_id = $hid;       
        $result = mysqli_query($connection,"SELECT * FROM `hospital_record` WHERE hr_uid='$blood_uid' and h_id='$hospital_id'");
    

      if(mysqli_num_rows($result)>0 ){
            $fetch= mysqli_fetch_assoc($result);
            
          
             $url= "delete.php?blood_uid=".$blood_uid."&&blood_type=".$arr1[$fetch['hr_bloodtype']];

            $field =  array("data_found"=>1 ) + $fetch;

            $json= json_encode($field);
        }else{

            $url= "insert.php?blood_uid=".$blood_uid;
             $field = array("data_found"=>0);

            $json= json_encode($field);
        }

        return $json;
}
}

// For login= http://localhost/lifelinefinal/response.php?key=login&&value={"email":"nair@gmail.com","password":"12345"}

function login(){
  global $json_array, $connection, $arr;
$email = $json_array['email'] ;
   $password = $json_array['password'] ; 

   if (!empty($email) && !empty($password) ){
  $query = "SELECT * FROM `registration` WHERE email='$email' AND Password='$password'";

   $results = mysqli_query($connection, $query);
   if( mysqli_num_rows($results) == 1){
    // echo "Login Successfull";
     $field=mysqli_fetch_assoc( $results );
     
     $json=json_encode($field);

     return $json;

   }
 }
}

// For delete= http://localhost/lifelinefinal/response.php?key=delete&&value={"id":"17","bloodtype":"1","blooduid":"111"}


function delete(){
   global $json_array, $connection, $arr;

 
    $hid= $json_array['id'];; // Extracting value of hospital id from set cookie
    $blood_uid= htmlentities($json_array['blooduid'] );

       

   $blood_type= $arr[$json_array['bloodtype'] ];

    if (!empty($blood_uid)) {

       
        /
        $result = mysqli_query($connection,"DELETE FROM `hospital_record` WHERE hr_uid='$blood_uid' and h_id='$hid'");
        $result = mysqli_query($connection, "UPDATE `hospital_database` SET hd_bloodunits = hd_bloodunits-1 WHERE `hospital_database`.`h_id` = '$hid' AND `hospital_database`.`hd_bloodtype`='$blood_type'" );
       

         $field = array("status"=>1);

            $json= json_encode($field);

        
    }else{
      $field = array("status"=>0);
      $json= json_encode($field);
    }
    return $json;
}


// For insert= http://localhost/lifelinefinal/response.php?key=insert&&value={"id":"17","bloodtype":"1","quantity":"1","expiry":"2017-12-12","blooduid":"111"}


function insert(){

     global $json_array, $connection, $arr;
    $negative = array("status"=>0);
  $blood_type= $arr[$json_array['bloodtype'] ];
  $expiry= $json_array['expiry'] ;
  $blood_uid=  $json_array['blooduid'] ;
  $hid = $json_array['id'];;

  if (!empty($blood_type) && !empty($expiry) && !empty($blood_uid)) {
     $result = mysqli_query($connection,"SELECT * FROM `hospital_record` WHERE hr_uid='$blood_uid' and h_id='$hid'");
   if( mysqli_num_rows($result)==0 ){
    $hospital_id = $hid;       // Using value hospital id from set cookie
    $date_added= Date("Y-m-d",time());
    $query = "INSERT INTO `hospital_record` (hr_uid,hr_bloodtype,hr_dateadded,hr_expiry, h_id) VALUES ('$blood_uid','$blood_type','$date_added','$expiry', '$hospital_id')";//Added hr_expiry in schema
    $result = mysqli_query($connection, $query);
    $expiry_result = mysqli_query($connection,"SELECT MIN(hr_expiry) expiry FROM `hospital_record` WHERE hr_bloodtype='$blood_type' and h_id='$hospital_id'");
    $fetch= mysqli_fetch_assoc($expiry_result);
    $expiry= $fetch['expiry'];
    if($result){
      $result = mysqli_query($connection, "UPDATE `hospital_database` SET hd_bloodunits = hd_bloodunits+1, hd_expiry='$expiry' WHERE `hospital_database`.`h_id` = '$hospital_id' AND `hospital_database`.`hd_bloodtype`='$blood_type'" );
      $field = array("status"=>1);
      $json= json_encode($field);
      
    }else{
      $field = array("statusA"=>0);
      $json= json_encode($field);
    }
   }
   else{
      $field = array("statusB"=>0);
      $json= json_encode($field);
    }
  }

  return $json;
}

// For finding hospitals= http://localhost/lifelinefinal/response.php?key=find&&value={"id":"17","latitude":"19.1584","longitude":"73.0294","bloodtype":"1","city":"Mumbai","quantity":"1"}

function view_hospitals(){

    global $json_array, $connection, $arr;

  $count=5;
 
 	$blood_type= $arr[$json_array['bloodtype'] ];
  $city=  $json_array['city'] ;
  $quantity=  $json_array['quantity'] ;
  $hid = $json_array['id'];
  
  if( (!empty($blood_type)&& !empty($city)  && !empty($quantity) ) || 1){
    try{
      $latitude= $json_array['latitude'];
      $longitude= $json_array['longitude'];
      $address_found=1;
    }catch(Exception $e){
      $address_found=0;
    }

    if($address_found){ 


 	    $query = "(SELECT id,hospital,address,latitude,longitude FROM registration where registration.city ='$city' and registration.id in
         (SELECT hospital_database.h_id FROM hospital_database where hd_bloodtype='$blood_type' and hd_bloodunits>=$quantity  ) ORDER BY SQRT( POWER($latitude-registration.latitude,2)+ POWER($longitude-registration.longitude,2) ) LIMIT $count )
         UNION 
         (SELECT id,hospital,address,latitude,longitude FROM registration JOIN hospital_database on hospital_database.h_id=registration.id 
          where registration.city ='$city' and hospital_database.hd_bloodtype='$blood_type' and hospital_database.hd_expiry>CURDATE() and hospital_database.hd_bloodunits>=$quantity ORDER BY hd_expiry LIMIT $count )";
      
 	    $result = mysqli_query($connection, $query);
     
      if($result ){
        $display=1; // For displaying tables
        $hospital_list= array();
        
  	   while($data= mysqli_fetch_assoc($result)){
            
          array_push( $hospital_list, $data);
         
        }
        $json=json_encode($hospital_list);
        return $json;


 	    }
    }
  }
}


 ?>
