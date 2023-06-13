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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $caregiverName = $_POST['username'];
  $caregiverPass = $_POST['password'];
  $elderID = $_POST['elder'];


  $sql="SELECT robot_table.robot_id, robot_table.robot_room FROM elder_list
  JOIN elder_caregiver 
  ON elder_list.elder_id = elder_caregiver.elder_id 
  JOIN caregiver_list
  ON caregiver_list.caregiver_id = elder_caregiver.caregiver_id
  JOIN robot_table 
  ON elder_list.robot_id = robot_table.robot_id
  WHERE elder_caregiver.elder_id='".$elderID."'
  AND caregiver_list.username='".$caregiverName."' 
  AND caregiver_list.password='".$caregiverPass."'
  ";

  $sql=mysqli_query($conn, $sql);
  if($sql->num_rows > 0) {
    while($row = $sql->fetch_assoc()) {
      $status = "berhasil";
      $robot_id = $row['robot_id'];
      $robot_room = $row['robot_room'];
    }
  } else {
    header("Location: /caregiver/index.php?login=gagal");
  }
}else {
  header("Location: /caregiver/index.php?err=need%20login");
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
    bottom: 0;
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
    text-shadow: 1px 1px 10px black;
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
    <!-- <h1 id="judul" style="top: 50px"><button onClick="getData();">Change ID</button></h1> -->
</div>
<div id="remote-videos-container">
  <h1 id="judul" style="top: 10px">Robot ID : <?php echo $robot_id;?> </h1>
</div>
</div>


<script>
var elem = document.documentElement;

/* View in fullscreen */
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}

var connection = new RTCMultiConnection();
connection.socketURL = 'https://private-server.uk.to:9449/';

connection.extra = {
    fullName: '<?php echo $caregiverName;?>',
    email: 'Your email',
    whatever: true
};

connection.session = {
    audio: true,
    video: true
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

// connection.iceTransportPolicy = 'all';
var localVideosContainer = document.getElementById('local-videos-container');
var remoteVideosContainer = document.getElementById('remote-videos-container');

connection.onclose = function(event) {
  
    window.location.replace('https://private-server.uk.to/?connection=closed%20by%20master');
};

connection.onUserStatusChanged = function(event) {
    var isOnline = event.status === 'online';
    var isOffline = event.status === 'offline';
    var targetUserId = event.userid;
    var targetUserExtraInfo = event.extra.fullName; // extra.fullName/etc
    // if(isOffline) { 

    // connection.checkPresence($robot_room, function(isRoomExist, roomid) {
    //     if (isRoomExist === true) {
    //       connection.join(roomid);
    //     } else {
    //         connection.close();
    //         connection.closeSocket();
    //         localVideosContainer.remove();
    //         alert('Host Closed!');
    //         window.location.replace('https://private-server.uk.to/');
    //     }
    // });
  // }
};

connection.onEntireSessionClosed = function(event) {
    console.info('Entire session is closed: ', event.sessionid, event.extra);
};

connection.ondisconnected = function (event) {
    console.log("Host disconnect cok!");
}
connection.onstream = function(event) {
    // document.body.appendChild( event.mediaElement );
    var video = event.mediaElement;
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
};

// var predefinedRoomId = prompt('Please enter room-id', 'xyzxyzxyz');

<?php 
if($status === "berhasil") {
  echo "connection.checkPresence('".$robot_room."', function(isRoomExist, roomid) {
    if (isRoomExist === true) {
      connection.join(roomid);
    } else {
        alert('Room doesnt exist!');
        window.location.replace('https://private-server.uk.to/');
    }
});";
}
?>

function changeId() {
  // connection.changeUserId('new-userid', function() {
  //       alert('Your userid is successfully changed to: ' + connection.userid);
  //   });
    var extra = connection.getExtraData('remote-userid');
    alert(extra.fullName);
}

function getConnection() {
  console.log(connection.getAllParticipants().length);
  // connection.close();
  // connection.closeSocket();
  // localVideosContainer.remove();
}

function getData() {
  connection.getAllParticipants().forEach(function(participantId) {
        var user = connection.peers[participantId];
        var hisUID = user.userid;
        var hisName = user.extra.fullName;
        var extra = connection.getExtraData(participantId);
        alert(extra.fullName + ' connected with you.');
    });

    var numberOfUsers = connection.getAllParticipants().length;
    alert(numberOfUsers + ' users connected with you.');
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
