<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VoV Automation Switcher create">
    <meta name="author" content="Dan Schueler">
    <meta name="generator" content="Dans 0.90.0">
    <!-- <meta http-equiv="refresh" content="2">     -->
  <title>VoV Source</title>

  <!-- Bootstrap core CSS -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">

    <style>
      
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

  </head>
  <body> 
   
  <main>
      <?php 
        ini_set('display_errors', 1);
        ini_set('log_errors',1);
        ini_set('error_log','./elog.txt');
	error_reporting(E_ALL);
	$cur = "ON";
	$trigger = 0;


	if(array_key_exists('buttonAuto', $_POST)) {
            buttonAuto();
        }
        else if(array_key_exists('buttonSR', $_POST)) {
            buttonSR();
        }
	else if(array_key_exists('buttonJBBS', $_POST)) {
            buttonJBBS();
        }
	
        function buttonAuto() {
	    SPLAutoOn();
	    switchBT("*001\n\r");
	    $trigger = 1;
        }
	
        function buttonSR() {
	    switchBT("*002\n\r");
	    SPLAutoStop();
	    $trigger = 1;
        }
	
	function buttonJBBS() {
	    switchBT("*003\n\r");
	    SPLAutoStop();
	    $trigger = 1;	    
        }
	
	function switchBT($BTstr) {
	    $address = '192.168.1.40';
	    $port = '56';
	    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

	    socket_connect($sock, $address, $port);
	    
	    socket_write($sock, $BTstr, strlen($BTstr));
        
	    $reply = socket_read($sock,  20);
	    socket_close($sock);
	}
	
	function SPLAutoOn() {
	    $SPLstr = "AutoOn\n\r";
	    $address = '192.168.1.25';
	    $port = '443';
	    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	    socket_connect($sock, $address, $port);
	    
	    socket_write($sock, $SPLstr, strlen($SPLstr));
        
	    $reply = socket_read($sock,  20);
	    socket_close($sock);	    	
        }
	
	function SPLAutoStop() {
	    $SPLstr = "Stop\n\r";
	    $address = '192.168.1.25';
	    $port = '443';
	    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	    
	    socket_connect($sock, $address, $port);
	    
	    socket_write($sock, $SPLstr, strlen($SPLstr));
        
	    $reply = socket_read($sock,  20);

	    socket_close($sock);
        }
		
    ?>
  
    <div class="px-4 py-5 my-5 text-center">
    <img class="d-block mx-auto mb-4" src="/logo.png" alt="" width="72" height="57">
    <h1 class="display-5 fw-bold">VoV Audio Source Switch</h1>
    <div class="col-lg-6 mx-auto">
    <p class="lead mb-4">Switch between Automation and one of the studios.</p>
    <div id="myDiv" class="d-grid gap-2 d-sm-flex justify-content-sm-center">
    <form method="post">
        <input type="submit" name="buttonAuto"
                class="button" value="Automation" />
          
        <input type="submit" name="buttonSR"
                class="button" value="Sunrise" />
		
	<input type="submit" name="buttonJBBS"
                class="button" value="JBBS Storefront" />
      </div>
    </div>
    <?php   
      $newstr = "Auto";
      
      echo "<br> <h3>";
	$address = '192.168.1.40';
	$port = '56';
	$BTstr = "*0SL";
	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

	socket_connect($sock, $address, $port);
	    
	socket_write($sock, $BTstr, strlen($BTstr));
        
	$newstr = socket_read($sock,  20);
	socket_close($sock);

//	echo $newstr;  // for debugging
//	echo "<br>";
	$newstr = trim($newstr);
	
 //       $ipos = stripos($newstr,"1");
	if ($newstr == "S0L,1,0,0,0")
	  echo "Automation";
	if ($newstr == "S0L,0,1,0,0")
	  echo "Sunrise";
	if ($newstr == "S0L,0,0,1,0")
	  echo "JBBS";
	  
	if ($trigger == 1) {
	    $trigger = 0;
//	    <script type="text/javascript" src="script.js"></script>;	    
	
	  }	

    
      echo "</h3>";
      
    ?>    
    </div>

  <div class="b-example-divider"></div>
 

    
//    <script src="/assets/dist/js/bootstrap.bundle.min.js"></script>

    </main>
  </body>
</html>
