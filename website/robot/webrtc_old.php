<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Video Conferencing using RTCMultiConnection</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <link rel="shortcut icon" href="/demos/logo.png">
  <link rel="stylesheet" href="/demos/stylesheet.css">
  <script src="../demos/menu.js"></script>

  <style>
    video{
        width: 100%;
    }
  </style>
</head>
<body>
  <header>
    <H1>Welcome to WebRTC</H1>
  </header>

<script src="../dist/RTCMultiConnection.min.js"></script>
<script src="../node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="../node_modules/socket.io/client-dist/socket.io.js"></script>

<button onClick="getConnection()">Count Connected User!</button>
<button onClick="fullscreen()">Fullscreen</button>

<!-- custom layout for HTML5 audio/video elements -->
<link rel="stylesheet" href="../dev/getHTMLMediaElement.css">
<script src="../dev/getHTMLMediaElement.js"></script>

<hr>

<div id="local-videos-container">
</div>

<hr>

<div id="remote-videos-container">
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
        // video.setAttribute('height', '100%');
        // video.setAttribute('width', '100%');
        localVideosContainer.appendChild(video);
        
    }
    if(event.type === 'remote') {
        remoteVideosContainer.appendChild(video);
    }
};

var predefinedRoomId = prompt('Please enter room-id', 'xyzxyzxyz');

connection.maxParticipantsAllowed = 4;
connection.openOrJoin(predefinedRoomId);

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
