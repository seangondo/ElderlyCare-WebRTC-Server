<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Video Conferencing using RTCMultiConnection</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <link rel="shortcut icon" href="/demos/logo.png">
  <link rel="stylesheet" href="/demos/stylesheet.css">
  <script src="/demos/menu.js"></script>
</head>
<body>
  <header>
    <H1>Welcome to WebRTC</H1>
  </header>

<script src="/dist/RTCMultiConnection.min.js"></script>
<!-- <script src="https://192.168.1.114:9449/socket.io/socket.io.js"></script> -->
  <!-- <script src="https://muazkhan.com:9001/socket.io/socket.io.js"></script> -->
<!-- <script src="/dist/RTCMultiConnection.min.js"></script> -->
<script src="/node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="/node_modules/socket.io/client-dist/socket.io.js"></script>

<button onClick="getConnection()">Count Connected User!</button>
<button onClick="fullscreen()">Fullscreen</button>

<!-- custom layout for HTML5 audio/video elements -->
<link rel="stylesheet" href="/dev/getHTMLMediaElement.css">
<script src="/dev/getHTMLMediaElement.js"></script>

<hr>

<div id="local-videos-container">
</div>

<hr>

<div id="remote-videos-container">
</div>

<script>
var connection = new RTCMultiConnection();

// this line is VERY_important
// connection.socketURL = 'https://muazkhan.com:9001/';
connection.socketURL = 'https://private-server.uk.to:9449/';

// all below lines are optional; however recommended.

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
    document.body.appendChild( event.mediaElement );
    var video = event.mediaElement;
    if(event.type === 'local') {
        localVideosContainer.appendChild(video);
    }
    if(event.type === 'remote') {
        remoteVideosContainer.appendChild(video);
    }
};

var predefinedRoomId = prompt('Please enter room-id', 'xyzxyzxyz');

connection.openOrJoin(predefinedRoomId);

function getConnection() {
  console.log(connection.getAllParticipants().length);
  connection.close();
  connection.closeSocket();
  localVideosContainer.remove();
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
