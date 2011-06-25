<h1>Event Fetcher Canvas Application</h1>
<p>This page will be used by cron or some other scripts which will be running continously to get the events from the
    users who already have authorized their facebook.</p>
<?php
/*
* @author Prabesh Shrestha prabesh708@gmail.com
*/

$eventsdata = json_decode(file_get_contents(
                        'https://graph.facebook.com/me/events?access_token=' .
                        "150265945018990|2eab97f4a7fef218f62d6a0d-738616327|u0bA01Gs4PUHFxbHEUph_6T-UnI")); //Replace this with access token stored previously
$dateformat = "j F Y"; // 25 December 2010 - see http://www.php.net/date for details
$timeformat = "g.ia"; // 11.45am

/*
This is the list of events we need for our application
*/
foreach ($eventsdata as $keys => $events) {
    foreach ($events as $key => $event) {
        $eventarray = (array) $event;
        echo "Name : " . $eventarray['name'] . "<br/>";
        echo "Start Date : " . date($dateformat, strtotime($eventarray['start_time'])) . "<br/>";
        echo "Start Time : " . gmdate($timeformat, strtotime($eventarray['start_time'])) . "<br/>";
        echo "End Date : " . date($dateformat, strtotime($eventarray['end_time'])) . "<br/>";
        echo "End Time : " . gmdate($timeformat, strtotime($eventarray['end_time'])) . "<br/>";
        echo "Location : " . $eventarray['location'] . "<br/>";
        echo "ID : " . $eventarray['id'] . "<br/>";
        echo "Status : " . $eventarray['rsvp_status'] . "<br/>";
        echo "<br/>";
        echo "<br/>";
    }
}
?>

