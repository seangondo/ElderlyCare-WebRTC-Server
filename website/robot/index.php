<!DOCTYPE html>

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

if(isset($_COOKIE[$cookie_robot_id]) && isset($_COOKIE[$cookie_robot_room])) {
    $sql=mysqli_query($conn, "SELECT * FROM robot_table WHERE robot_id='" .$_COOKIE[$cookie_robot_id]."' AND robot_room='" .$_COOKIE[$cookie_robot_room]. "'");
    if($sql->num_rows > 0) {
        // header("Location: test.php?login=berhasil&robot_id=".$_COOKIE[$cookie_robot_id]."&robot_room=".$_COOKIE[$cookie_robot_room]."");
        header("Location: webrtc.php");
    } else {
        setcookie($cookie_robot_id , "", time()-3600);
        setcookie($cookie_robot_room , "", time()-3600);
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET") {
    if($_GET['login'] == 'gagal') {
        echo "<script>alert('Login Gagal!')</script>";
    } else if($_GET['login'] == 'berhasil') {
        echo "<script>alert('Login Berhasil!')</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    if(isset($_POST['Login'])) {
        $name = $_POST['username'];
        $pass = $_POST['password'];
        if (empty($name)) {
            $errorUser = true;
        } else {
            $errorUser = false;
        }
        if (empty($pass)) {
            $errorPass = true;
        } else {
            $errorPass = false;
        }
        if(!empty($name) && !empty($pass)) {
            $sql=mysqli_query($conn, "SELECT * FROM caregiver_list WHERE username='" .$name."' AND password='" .$pass. "'");
            if($sql->num_rows > 0) {
                do {
                    $robot_id = rand(20001, 29999);
                    $sql1=mysqli_query($conn, "SELECT * FROM robot_table WHERE robot_id='" .$name."'");
                    $data = $sql1->num_rows;
                } while ($data > 0);
                $rand_room = generateRandomString();
                $sql2=mysqli_query($conn, "INSERT INTO robot_table (robot_id, robot_room) VALUES ('" .$robot_id. "', '" .$rand_room. "')");
                setcookie($cookie_robot_id, $robot_id, time() + (10 * 365 * 24 * 60 * 60), "/");
                setcookie($cookie_robot_room, $rand_room, time() + (10 * 365 * 24 * 60 * 60), "/");
                header("Location: webrtc.php");
            } else {
                header("Location: ?login=gagal");
            }
        }
    }
}
?>

<head>
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

        .inpt {
            background-color: #F5F5F5;
            border: 2px solid #333333;
            border-width: 1px;
            padding: 10px;
            text-align: left;
            border-radius: 10px;
            transition: 0.3s;
            margin-top: 10px;
            width: 95%;
            text-shadow: 0px 0px 0 grey;

            color: #000000;
            font-weight: 500;
            font-size: 16px;
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

<body onload="">
    <div class="header">
        <p>Robot Setup Page RTC</p>
    </div>
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h1>GAMBAR RTC</h1>
            </div>
            <form method="post" action="">
                <div class="center">
                    <input class="inpt" type="text" name="username" placeholder="Caregiver Username" value="<?php echo $name;?>">
                    <p style="color: red"> <?php if($errorUser){ echo "Username empty!";} else {echo "";}?> </p>
                </div>
                <div class="center">
                    <input class="inpt" type="password" name="password" placeholder="Caregiver Password">
                    <p style="color: red"> <?php if($errorPass){ echo "Password empty!";} else {echo "";}?> </p>
                </div>
                <div class="center">
                    <input type="submit" class="btn" name="Login" value="Login">
                </div>
            </form>
            <div class="center-text">
                <p>Don't have account? <a href="https://private-server.uk.to/test">Create an Account</a></p>
                <p>Need help? <a href="https://www.google.com/">Contact us</a></p>
            </div>
            <div class="card-footer"> </div>
        </div>
    </div>
</body>
<?php
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
?>
</html>