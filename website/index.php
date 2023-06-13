<!DOCTYPE html>

<head>
<?php
    $servername = "private-server.uk.to:3306";
    $username = "admin";
    $password = "randpwsocool";
    $db = "elderly_care";

    $cookie_robot_id = "robot_id";
    $cookie_robot_room = "robot_room";

    // Create connection
    $conn = mysqli_connect($servername,$username, $password);
    if(!$conn){
        echo "Koneksi gagal";
    }
    else {
        mysqli_select_db($conn, $db);
    }
?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    @media screen and (min-width: 200px) /*and (max-width: 500px)*/ {
        body {
            background-color: #6FB1FC;
            margin: 0;
        }
        h1 {
            padding: 20px 20px;
            text-align: center;
            color: #333333;
            font-weight: 700;
            font-size: 50px;
        }

        .main-content {
            padding-top: 100px;
        }

        .header {
            /* width: 100%;
            height: 100px;
            background-color: blue; */
        
            background: #F5F5F5;
            color: #333333;
            text-align: center;
            box-shadow: 0 0 20px rgba(0,0,0,.1);
            padding: 0 20px;
            font-weight: 700;
            font-size: 40px;
        }

        .btn {
            background-color: #F5F5F5;
            border: 3px solid #333333;
            border-width: 1px;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            opacity: 0.6;
            transition: 0.3s;
            margin-top: 10px;
            width: 100%;
            text-shadow: 0px 0px 0 grey;

            color: #000000;
            font-weight: 700;
            font-size: 20px;
        }
        .btn:hover {
            background-color: #2E8B57;
            opacity: 1;
            text-shadow: 1px 1px 0 grey;
            color: #333333;
        }

        .card {
            background-color: #F5F5F5;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            min-width: 100px;
            max-width: 500px;

            /* height: 600px; */
            margin-left: auto;
            margin-right: auto;
            position: relative;

            border-radius: 25px;
        }
        
        /*.card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        } */

        .center {
            margin-top: 10px;
            padding: 0 50px;
            margin-bottom: 10px;
        }

        .center-text {
            margin-top: 20px;
            padding: 0 50px;
            margin-bottom: 10px;
            text-align: center;
        }

        .card-header {
            padding-top:20px;
        }

        .card-footer {
            padding-bottom:20px;
        }

        p{
            margin-top: 0px;
            margin-bottom: 0px;
        }
    }
    
</style>
</head>

<body>
    <div class="header">
        <p> Elderly Care RTC</p>
    </div>
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h1>GAMBAR RTC</h1>
            </div>
            <div class="center">
                <button class="btn" onClick="window.location='https://private-server.uk.to/robot/'">Log in as Elderly Care Robot</button>
            </div>
            <div class="center">
                <button class="btn" onClick="window.location='https://private-server.uk.to/caregiver/'">Log in as Care Giver</button>
            </div>
            <!-- <div class="center-text">
                <p>Don't have account? <a href="https://private-server.uk.to/test">Create an Account</a></p>
                <p>Need help? <a href="https://www.google.com/">Contact us</a></p>
            </div> -->
            <div class="card-footer"> </div>
        </div>
    </div>
    
    <?php
    function console_log() {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        $js_code = 'console.log(' . $useragent . ');';
        echo $js_code;
        echo(rand(20001,29999));
        header("Location: https://private-server.uk.to/coba.php");
    }
    ?>
</body>
</html>