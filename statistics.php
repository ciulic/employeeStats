<?php
header('Content-Type: application/json');

include('setup.php');

$userID = $_REQUEST['EID'];

if (!empty($_POST)) {
    if ((int)$userID > 0) {
        if ($_POST['date'] != '' && $_POST['enterTime'] != '' && $_POST['leaveTime'] != '') {
            $DB = new DB('statistics');
            $stats = array(
                'date' => $_POST['date'],
                'enterTime' => $_POST['enterTime'],
                'leaveTime' => $_POST['leaveTime']
            );
            
            $date1 = date_create($_POST['date'].' '.$_POST['enterTime'].':00');
            $date2 = date_create($_POST['date'].' '.$_POST['leaveTime'].':00');

            $interval = date_diff($date2, $date1);
            $hours = $interval->format('%h.%i');
            
            $array = $DB->addUserStats($_POST['EID'], $hours, $stats);
            $DB->saveStats($array);
            
            $stats['hours'] = $hours;
            $stats['date'] = date('d M Y', strtotime($stats['date']));
            $return = array(
                'success' => $stats
            );
        } else {
            $return = array(
                'error' => 'All fields are mandatory.'
            );
        }
    } else {
        $return = array(
            'error' => 'No User ID passed.'
        );
    }
    
    echo json_encode($return);
    die();
}

$DB = new DB('statistics');
$DB->setUserID($userID);
$DB->getContent();
?>

