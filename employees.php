<?php
header('Content-Type: application/json');

include('setup.php');

if (!empty($_POST)) {
    if ($_POST['firstName'] != '' && $_POST['lastName'] != '' && $_POST['title'] != '') {
        $DB = new DB('employees');
        $employee = array(
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'title' => $_POST['title']
        );
        
        $array = $DB->addEmployee($employee);
        $DB->saveEmployees($array);
        
        $employee['employeeID'] = $array['lastInsertedId'];
        $return = array(
            'success' => $employee
        );
    } else {
        $return = array(
            'error' => 'All fields are mandatory.'
        );
    }
    
    echo json_encode($return);
    die();
}

$DB = new DB('employees');
$DB->getContent();
?>

