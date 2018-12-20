<?php

//phpinfo();
include('dbconfig.php');

$result = mysql_query("select * from code_coverage where sub_system = 'prrq' and Component IS NULL",$conn);

$rows = $result->mysql_num_rows();
 echo "Row Count - ".$rows;

?>
