<?php
/*
*  @author Prabesh Shrestha prabesh708@gmail.com
*/

require 'src/facebook.php';
// Create our Application instance.
//These information will be provided when registering an application in facebook
$facebook = new Facebook(array(
            'appId' => '150265945018990',
            'secret' => 'c5393ab40b6db03ae013689a5376e370',
            'cookie' => true,
        ));

$session = $facebook->getSession();                        //
echo "Session Information <br/>";
echo "Access Token : ".$session['access_token']."<br/>";   // Must be saved for accessing the user information in the future with cron.php
echo "user ID : ".$session['uid']."<br/>";                 // Facebook User ID

$me = null;
// Session based API call.
if ($session) {
    try {
        $uid = $facebook->getUser();
        $me = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}

// login or logout url will be needed depending on current user state.
if ($me) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl();
}
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title>Event Fetcher Canvas Application on facebook</title>
        <style>
            body {
                font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
            }
            h1 a {
                text-decoration: none;
                color: #3b5998;
            }
            h1 a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId   : '<?php echo $facebook->getAppId(); ?>',
                    session : <?php echo json_encode($session); ?>, // don't refetch the session when PHP already has it
                    status  : true, // check login status
                    cookie  : true, // enable cookies to allow the server to access the session
                    xfbml   : true, // parse XFBML
                });

                // whenever the user logs in, we refresh the page
                FB.Event.subscribe('auth.login', function() {
                    window.location.reload();
                });
            };

            (function() {
                var e = document.createElement('script');
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
        </script>

        <?php if ($me): ?>
        <h1>Event Fetcher Canvas Application</h1>
        <p>This application will fetch the events from your facebook.</p>
        <!--<a href="<?php echo $logoutUrl; ?>">
                        <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif">
                    </a>-->
        <?php else: ?>
                <div>
                    Login &amp; XFBML:
                    <fb:login-button perms="email,offline_access,user_events"
                                     show-faces="true"></fb:login-button>
                </div>
        <?php endif ?>

        <?php if ($me): ?>
        <?php
			// Fetching Events with graph API similarly other information can be fetched but not needed in our application right now
                    $eventsdata = json_decode(file_get_contents(
                                            'https://graph.facebook.com/me/events?access_token=' .
                                            $session['access_token']));
		    $dateformat="j F Y"; // 26 December 2010 - see http://www.php.net/date for details
		    $timeformat="g.ia"; // 11.25am

		    /*
			For now The list of events are displayed to the logged in user
			This need not be displayed later Instead some information about the event Fetcher website can be displayed and users can be 				redirected to that site
		    */

                    foreach ($eventsdata as $keys => $events) {
                        foreach ($events as $key => $event) {
                            $eventarray = (array) $event;
                            echo "Name : " . $eventarray['name'] . "<br/>";
			    echo "Start Date : ". date($dateformat, strtotime( $eventarray['start_time'] )). "<br/>";
                            echo "Start Time : " . gmdate($timeformat, strtotime( $eventarray['start_time'] )) . "<br/>";
			    echo "End Date : ". date($dateformat, strtotime( $eventarray['end_time'] )). "<br/>";
                            echo "End Time : " . gmdate($timeformat, strtotime( $eventarray['end_time'] )) . "<br/>";
                            echo "Location : " . $eventarray['location'] . "<br/>";
                            echo "ID : " . $eventarray['id'] . "<br/>";
                            echo "Status : " . $eventarray['rsvp_status'] . "<br/>";
                            echo "<br/>";
                            echo "<br/>";
                        }
                    }
        ?>
        <?php else: ?>
        <?php endif ?>
    </body>
</html>

