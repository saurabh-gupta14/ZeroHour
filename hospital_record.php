<?php
 require_once('connect.php');
 if (!isset($_COOKIE['hospitalid']))
    { // If cookie is not set redirect to login page
        header("Location: login.php");
    }
if( isset($_POST['blood_uid']) && $_POST['blood_uid'] ){
 
    $hid= $_COOKIE['hospitalid']; // Extracting value of hospital id from set cookie
    $blood_uid= htmlentities($_POST['blood_uid'] );

        $arr =  array("A+"=> 1,
                      "A-"=> 2,
                      "B+"=> 3,
                      "B-"=> 4,
                      "AB+"=> 5,
                      "AB-"=> 6,
                      "O+"=> 7,
                      "O-"=> 8 );
     
    if (!empty($blood_uid)) {

        $hospital_id = $hid;       // Using value hospital id from set cookie
        //print_r("Salim");
        /*$date_added= Date("Y-m-d",time());
        $query = "INSERT INTO `hospital_record` (hr_uid,hr_bloodtype,hr_dateadded,hr_expiry, h_id) VALUES ('$blood_uid','$blood_type','$date_added','$expiry', '$hospital_id')";//Added hr_expiry in schema
        $result = mysqli_query($connection, $query);*/
        $result = mysqli_query($connection,"SELECT * FROM `hospital_record` WHERE hr_uid='$blood_uid' and h_id='$hospital_id'");
       
        
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Hospital Record</title>
    <style>
        table{
            border: 6px solid black;
        }
        table th{
            border: 1px solid black;
        }
        table td{
            border: 1px solid black;
        }
        label{
            
            color:black;
        }
        body{
            background-color: lightgray;
           /* background-image:url('1.png');*/
        }
        .button{ background-color:black;
        display: block;}
        
        .input:focus{outline: none !important;
        border-color: black;
        box-shadow: 0 0 10px black ;}
        
        </style>
         <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ZeroHour</title>

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
</head>
<body>
<div>
<?php require_once 'navbar3.php'; ?>
<br><br><br>
<center>
    <form method="POST">
    

    <label for="blood_uid">Blood unit unique id:</label>                    
    <input type="text" name="blood_uid" id="blood_uid" placeholder="Enter unique key here.."><br/><br>
        
        <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Confirm</button>
                            </div>
                       
        
  <!--  <input type="submit" value="Confirm" class="button"> -->
    </form>
    <br>
    <!-- Use display_result class for styling -->
    <?php 

    if(isset($_POST['blood_uid']) && $_POST['blood_uid']){
        if(mysqli_num_rows($result)>0 ){
            $fetch= mysqli_fetch_assoc($result);
            print_r($fetch);
            echo "<div class='display_result'><h5>Unique id  :  ".$fetch['hr_uid']."<br><br>",
            "Blood type  :  ".$fetch['hr_bloodtype']."<br><br>",
            "Date added  :  ".$fetch['hr_dateadded']."<br><br>",
            "Expiry date  :  ".$fetch['hr_expiry']."</h5><br><br>";

            $url= "delete.php?blood_uid=".$blood_uid."&&blood_type=".$arr[$fetch['hr_bloodtype']];
            echo "<a href='$url'><button>Delete Record</button></a></div>";
        }else{

            $url= "insert.php?blood_uid=".$blood_uid;
            echo "<div class='display_result'><h5>Entry not found</h5><br><br>";
            echo "<a href='$url'><button>Insert Record</button></a></div>";
        }

    }
        ?>
    <br>
    <h2><font color="black">Update History:</font></h2>
    <table width=80% height=80% >
            <tr style="text-color=black;">
                <th  >Unique id</th>
                <th >Blood Group</th>
                <th >Date Added</th>
                <th >Expiry</th>
            </tr>
            <?php
           
            if(isset( $_COOKIE['hospitalid'])){
                $hid= $_COOKIE['hospitalid'];
                $result=mysqli_query($connection, "SELECT * FROM hospital_record where h_id='$hid' ORDER BY hr_dateadded DESC LIMIT 20");
                // Loop for displaying each row
                if($result){
                    while($row=mysqli_fetch_assoc( $result )){
                        echo '<tr>',
                            '<td>', $row['hr_uid'] ,'</td>',
                            '<td>', $row['hr_bloodtype'] ,'</td>',
                            '<td>', $row['hr_dateadded'] ,'</td>',
                            '<td>' , $row['hr_expiry'] , '</td>',
                        '</tr>';
                    }
                }
                else
                    echo "Error occurred please try again!";

            }
            ?>
        </table></center>
        <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright"><font color="black">Copyright &copy; ZeroHour 2017</font></span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-heartbeat"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-heart"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-medkit"></i></a>
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
</body>
</html>