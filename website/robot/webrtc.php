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

if(isset($_COOKIE[$cookie_robot_id]) && isset($_COOKIE[$cookie_robot_room])) {
    $sql=mysqli_query($conn, "SELECT * FROM robot_table WHERE robot_id='" .$_COOKIE[$cookie_robot_id]."' AND robot_room='" .$_COOKIE[$cookie_robot_room]. "'");
    if($sql->num_rows > 0) {
        echo "<script>console.log('Debug Objects: " . $_COOKIE[$cookie_robot_id] . "' );</script>";
        $current = "robot";
        $robot_id = $_COOKIE[$cookie_robot_id];
        $robot_room = $_COOKIE[$cookie_robot_room];
    } else {
        header("Location: index.php?err=Login%20first!");
    }
  } else {
    header("Location: index.php?err=Login%20first!");
  }

?>
  <meta charset="utf-8">
  <title>Elderly Care WebRTC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <!-- <link rel="shortcut icon" href="/demos/logo.png"> -->
  <link rel="stylesheet" href="/demos/stylesheet.css">
  <script src="../demos/menu.js"></script>
  <style>
    body {
      background-color: #000000
    }
    #wrap_video {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    }

    #local-videos-container {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
    }
    
    #remote-videos-container {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    }

    #judul {
    right: 0;
    position: absolute;
    z-index: 100;
    }

    video{
        pointer-events: none;
        border: 3px solid #333333;
    }
  </style>

</head>
<body>

<script src="../dist/RTCMultiConnection.min.js"></script>
<script src="../node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="../node_modules/socket.io/client-dist/socket.io.js"></script>
<link rel="stylesheet" href="../dev/getHTMLMediaElement.css">
<script src="../dev/getHTMLMediaElement.js"></script>

<div id="wrap_video">
<div id="local-videos-container">
    <h1 id="judul" style="top: 10px">Robot ID : <?php echo $robot_id;?> </h1>
    <h1 id="judul" style="top: 50px"><button onClick="openFullscreen();">Full Screen</button><button onClick="closeFullscreen();">Close full screen</button></h1>
    <h1 id="judul" style="top: 90px"><button onClick="getData();">Get Data</button></h1>
    <h1 id="judul" style="top: 130px">Caregiver : <span id="p1"></span> </h1>
</div>
  <div id="remote-videos-container">
</div>
</div>

<script>

var elem = document.documentElement;
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}

function closeFullscreen() {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.webkitExitFullscreen) { /* Safari */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE11 */
    document.msExitFullscreen();
  }
}

var connection = new RTCMultiConnection();
connection.socketURL = 'https://private-server.uk.to:9449/';
connection.session = {
    audio: true,
    video: true
};

connection.extra = {
    fullName: 'Host',
    email: 'Your email',
    whatever: true
};

connection.sdpConstraints.mandatory = {
    OfferToReceiveAudio: true,
    OfferToReceiveVideo: true
};

// first step, ignore default STUN+TURN servers
connection.iceServers = [];

// // second step, set STUN url
// connection.iceServers.push({
//     urls: 'stun:private-server.uk.to:5349'
// });


// // last step, set TURN url (recommended)
// connection.iceServers.push({
//     urls: 'turn:private-server.uk.to:5349',
//     credential: 'sean1234',
//     username: 'seangondo'
// });

// second step, set STUN url
connection.iceServers.push({
    urls: 'stun:private-server.uk.to:5349'
});


// last step, set TURN url (recommended)
connection.iceServers.push({
    urls: 'turn:private-server.uk.to:5349',
    credential: 'sean1234',
    username: 'seangondo'
});

// connection.iceProtocols = {
//     udp: true,
//     tcp: false
// };


connection.iceProtocols = {
    udp: true,
    tcp: true
};

// connection.iceTransportPolicy = 'all';

var width = 720;
var height = 480;

var supports = navigator.mediaDevices.getSupportedConstraints();

var constraints = {};
if (supports.width && supports.height) {
    constraints = {
        width: width,
        height: height
    };
}

connection.applyConstraints({
    video: constraints
});

var localVideosContainer = document.getElementById('local-videos-container');
var remoteVideosContainer = document.getElementById('remote-videos-container');

connection.onstream = function(event) {
    // document.body.appendChild( event.mediaElement );
    var video = event.mediaElement;
    console.log(event.type);
    if(event.type === 'local') {
        video.setAttribute('height', '25%');
        video.setAttribute('width', '25%');
        video.setAttribute('top', '0');
        video.setAttribute('right', '0');
        localVideosContainer.appendChild(video);
        
    }
    if(event.type === 'remote') {
        video.setAttribute('height', '100%');
        video.setAttribute('width', '100%');
        video.setAttribute('position', 'fixed');
        video.setAttribute('top', '0');
        video.setAttribute('left', '0');
        remoteVideosContainer.appendChild(video);
    }
    getData();
};

// var predefinedRoomId = prompt('Please enter room-id', 'xyzxyzxyz');
connection.autoCloseEntireSession = true;
connection.maxParticipantsAllowed = 2;
<?php 
if ($current === "robot") {
    echo "
    connection.open('$robot_room', function(isRoomOpened, roomid, error) {
    
        if(error) {
            alert(error);
            window.location.replace('https://private-server.uk.to/');
        }
    
        if(isRoomOpened === true) {
            console.log('Successfully created the room.');
        }
    });";
}
?>

function getData() {
  connection.getAllParticipants().forEach(function(participantId) {
        var user = connection.peers[participantId];
        var hisUID = user.userid;
        var hisName = user.extra.fullName;
        var extra = connection.getExtraData(participantId);
        document.getElementById("p1").innerHTML = hisName;
        // alert(hisName + ' connected with you.');
    });

    var numberOfUsers = connection.getAllParticipants().length;
    // alert(numberOfUsers + ' users connected with you.');
}

function getConnection() {
  console.log(connection.getAllParticipants().length);
  // connection.close();
  // connection.closeSocket();
  // localVideosContainer.remove();
}

function fullscreen() {
  document.documentElement.webkitRequestFullScreen();
}

</script>
  <footer>
    <small id="send-message"></small>
  </footer>

  <script src="https://www.webrtc-experiment.com/common.js"></script>
</body>
</html>
