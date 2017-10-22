<?php
 require_once('connect.php');
 if (!isset($_COOKIE['hospitalid']))
    { // If cookie is not set redirect to login page
        header("Location: login.php");
    }
if( isset($_GET['blood_uid']) && isset($_GET['blood_type']) ){
 
    $hid= $_COOKIE['hospitalid']; // Extracting value of hospital id from set cookie
    $blood_uid= htmlentities($_GET['blood_uid'] );

        $arr =  array(1 =>  "A+",
                      2 =>  "A-",
                      3 =>  "B+",
                      4 =>  "B-",
                      5 =>  "AB+",
                      6 =>  "AB-",
                      7 =>"O+" ,
                      8 => "O-");

   $blood_type= $arr[$_GET['blood_type'] ];
    
    if (!empty($blood_uid)) {
        
       
        /*$date_added= Date("Y-m-d",time());
        $query = "INSERT INTO `hospital_record` (hr_uid,hr_bloodtype,hr_dateadded,hr_expiry, h_id) VALUES ('$blood_uid','$blood_type','$date_added','$expiry', '$hospital_id')";//Added hr_expiry in schema
        $result = mysqli_query($connection, $query);*/
        $result = mysqli_query($connection,"DELETE FROM `hospital_record` WHERE hr_uid='$blood_uid' and h_id='$hid'");
        $result = mysqli_query($connection, "UPDATE `hospital_database` SET hd_bloodunits = hd_bloodunits-1 WHERE `hospital_database`.`h_id` = '$hid' AND `hospital_database`.`hd_bloodtype`='$blood_type'" );
        
        header("Location: hospital_record.php");
        /*$fetch= mysqli_fetch_assoc($result);
        if($result){

            foreach($arr as $key => $value){
            echo "<h5>".$key."  :  ".$value."</h5><br><br>"
            }
            $url= "delete.php?blood_uid=".$blood_uid;
            echo "<a href='$url'><button>Delete Record</button></a>";
        }else{
            $url= "insert.php?blood_uid=".$blood_uid;
            header("Location: $url");
        }
        */
    }
}
?>