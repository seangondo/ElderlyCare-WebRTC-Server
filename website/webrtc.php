<!DOCTYPE html>
<html lang="en" dir="ltr">
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
  <meta charset="utf-8">
  <title>Elderly Care WebRTC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <!-- <link rel="shortcut icon" href="/demos/logo.png"> -->
  <link rel="stylesheet" href="/demos/stylesheet.css">
  <script src="../demos/menu.js"></script>
  <style>
    #wrap_video {
    position: absolute;
    width: 100%;
    top: 0;
    left: 0;
    }
    #local-videos-container {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 100;
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
</div>
<div id="remote-videos-container">
</div>
</div>


<script>
var connection = new RTCMultiConnection();
connection.socketURL = 'https://private-server.uk.to:9449/';

connection.session = {
    audio: true,
    video: true
};

connection.sdpConstraints.mandatory = {
    OfferToReceiveAudio: true,
    OfferToReceiveVideo: true
};

var localVideosContainer = document.getElementById('local-videos-container');
var remoteVideosContainer = document.getElementById('remote-videos-container');

connection.onstream = function(event) {
    // document.body.appendChild( event.mediaElement );
    var video = event.mediaElement;
    console.log(event.type);
    if(event.type === 'local') {
        video.setAttribute('width', '35%');
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
};

var predefinedRoomId = prompt('Please enter room-id', 'xyzxyzxyz');

connection.checkPresence(predefinedRoomId, function(isRoomExist, roomid) {
    if (isRoomExist === true) {
        connection.join(roomid);
    } else {
        alert('Room doesnt exist!');
    }
});

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
