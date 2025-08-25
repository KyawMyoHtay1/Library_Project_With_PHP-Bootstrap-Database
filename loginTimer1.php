<?php 
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Timer</title>    
    <link rel="stylesheet" type="text/css" href="css/styles.css?<?php echo time(); ?>" />
</head>
<body class="logintimer">

    <div id="Timer"></div>
    
    <script>
    // Set the date we're counting down to
    var now = new Date();
    var month = now.getMonth(); // Month is 0-indexed (0 = January)
    var day = now.getDate();
    var year = now.getFullYear();
    var hour = now.getHours();
    var minutes = now.getMinutes() + 10; // Adding 10 minutes
    var seconds = now.getSeconds() + 2; // Adding 2 seconds
    if (seconds >= 60) {
        seconds -= 60;
        minutes += 1;
    }
    if (minutes >= 60) {
        minutes -= 60;
        hour += 1;
    }
    var time = hour + ":" + minutes + ":" + seconds;
    var ResetTime = new Date(year, month, day, hour, minutes, seconds).getTime();

    var x = setInterval(function() {
     
        var now = new Date().getTime(); 
        var distance = ResetTime - now; 
       
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("Timer").innerHTML = "<h1>Login is blocked</h1>" + minutes + "m " + seconds + "s ";
       
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("Timer").innerHTML = "<?php session_destroy();  ?>";
            window.location.href = 'StaffRegister.php';
        }
    }, 1000); // 1 second = 1000 milliseconds
    </script>

</body>
</html>
