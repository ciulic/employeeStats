<?php
class DB {
    var $file = '';
    var $jsonContent = '';
    var $userID = 0;
    
    function __construct($file) {
        $this->file = $file.'.json';
        
        $this->readFile();
    }

    function __destruct() {
        
    }
    
    function setUserID($userID) {
        $this->userID = $userID;
        
        $this->getContentByUserID();
    }
    
    function readFile() {
        $file = 'inc/database/'.$this->file;
        $handle = fopen($file, 'r');
        $content = fread($handle, filesize($file));
        fclose($handle);
        
        $this->jsonContent = $content;
    }
    
    function getContentByUserID() {
        $array = json_decode($this->jsonContent, true);
        
        #FUNCTIONS::debug($array);
        
        $content = $array['rows'][0][$this->userID];
        if (empty($content)) $content = array();
        $this->jsonContent = json_encode($content);
    }
    
    function addUserStats($EID, $hours, $stats) {
        $array = json_decode($this->jsonContent, true);
        $EIDrows = $array['rows'][0][$EID];

        $EIDrows['totalHours'] += $hours;
        $EIDrows['dates'][0][$stats['date']] = array_merge((array)$EIDrows['dates'][0][$stats['date']], array(
            'date' => $stats['date'],
            'hours' => $hours,
            'entries' => array_merge((array)$EIDrows['dates'][0][$stats['date']]['entries'], array(array(
                'enterTime' => $stats['enterTime'],
                'leaveTime' => $stats['leaveTime']
            )))
        ));
        
        $array['total'] += 1;
        $array['rows'][0][$EID] = $EIDrows;
        
        return $array;
    }
    
    function addEmployee($employee) {
        $array = json_decode($this->jsonContent, true);
        
        $array['total'] += 1;
        $array['lastInsertedId'] += 1;
        
        $employee['employeeID'] = $array['lastInsertedId'];
        $array['rows'] = array_merge(array($employee), (array)$array['rows']);
        
        return $array;
    }
    
    function saveEmployees($array) {
        $file = 'inc/database/'.$this->file;
        $handle = fopen($file, 'w+');
        $jsonContent = json_encode($array);
        
        fwrite($handle, $jsonContent);
        fclose($handle);
    }
    
    function saveStats($array) {
        $file = 'inc/database/'.$this->file;
        $handle = fopen($file, 'w+');
        $jsonContent = json_encode($array);
        
        fwrite($handle, $jsonContent);
        fclose($handle);
    }
    
    function getContent() {
        echo $this->jsonContent;
    }
}

?>
