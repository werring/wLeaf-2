<?php
require_once "/home/wleaf/wleafv2/database/mysql.php";

new Database_Mysql(true,false);

$time = time();
$oldDates = $time - (60*60*24*7);

$qry = "SELECT * FROM access WHERE `banned`<='".$oldDates."' AND `banned`>1000000000"; 
$data = Database_Mysql::advancedSelect($qry);

foreach($data as $key => $selectedData){
    if(is_numeric($key)){
        echo "DELETE " . $selectedData['account'] . " ACCESS: " . $selectedData['access'] . PHP_EOL;
        $fp = fopen("/home/wleaf/wleafv2/removeUserFromZNC/" . $selectedData['account'],'w');
        fwrite($fp,$selectedData['account']."|".$selectedData['auth']."|".$selectedData['access'].PHP_EOL);
        fclose($fp);
    }
}
$qry = "SELECT * FROM access WHERE `banned`<='".$oldDates."' AND `banned`>1000000000"; 
Database_Mysql::sqlQry($qry);
?>