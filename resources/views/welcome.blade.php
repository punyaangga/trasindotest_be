<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('e62ec02068708509e581', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('portal-dev-development');
    channel.bind('portal-dev-development', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
