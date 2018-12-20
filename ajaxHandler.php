<?php
    session_start();
    $posted_data = $_POST['json'];
    $data = json_decode($posted_data,true);
    //var_dump($data);
    $mode = $data[0];
    $range = $data[1];
    //Type - EFR or Bug List
    $type = $data[2];

	$rangeStr = array_unique(explode(',',$range));
	$record = array('1%-25%','26%-50%','51%-75%','76%-90%', '91%-100%');
	$extra = "";
	if((in_array("91%-100%",$rangeStr)) and (sizeof($rangeStr) < 2)){
		$extra= "(code_coverage.Coverage between 90.01 and 100.00)";
	}else if(count(array_intersect($rangeStr,$record)) == count($record)){
		$extra = "(code_coverage.Coverage between 0.01 and 100.00)";
	}else{
		foreach ($rangeStr as $value) {
            if('1%-25%'==$value) {
                $extra = "(code_coverage.Coverage between 0.01 and 25.00) ";
            }
            if('26%-50%'==$value){
                if(!empty($extra)){
                    $extra = $extra." or (code_coverage.Coverage between 25.01 and 50.00) ";
                }else {
                    $extra ="(code_coverage.Coverage between 25.01 and 50.00) ";
                }
            }
            if('51%-75%'==$value){
                if(!empty($extra)){
                    $extra = $extra." or (code_coverage.Coverage between 50.01 and 75.00) ";
                } else {
                    $extra ="(code_coverage.Coverage between 50.01 and 75.00) ";
                }
            }
            if('76%-90%'==$value){
                if(!empty($extra)) {
                    $extra = $extra." or (code_coverage.Coverage between 75.01 and 90.00) ";
                }else {
                    $extra ="(code_coverage.Coverage between 75.01 and 90.00) ";
                }
            }
            if('91%-100%'==$value){
                if(!empty($extra)) {
                    $extra = $extra." or (code_coverage.Coverage between 90.01 and 100.00) ";
                }else {
                    $extra ="(code_coverage.Coverage between 90.01 and 100.00) ";
                }
            }
        }
    }

	if ($extra !== false){
	    $extra ="AND (".$extra.")";
	}

	//Setting Session variables
	$_SESSION["rangeQuery"] = $extra;

    if($type == "EFR"){
		$from_efrnumber = $data[3];
		$to_efrnumber = $data[4];
		$branch = $data[5];
		$_SESSION["from_EFRNumber"] = $from_efrnumber;
		$_SESSION["to_EFRNumber"]= $to_efrnumber;
		$_SESSION["efr_branch"]=$branch;
		if($mode == "manual"){
			echo " true";
			echo "manualefr";
		}else if($mode == "automatic"){
			echo "Enters here";
			echo " true";
			echo "automaticefr";
		}
		//$typecheck = $extra;
		//$typechoose=$_POST['example']; - typechoose is EFR or Bug List

    }else if($type == "CFD"){

		//$cfd1=$_POST['cfd1'];
		$cfd = $data[3];
		$_SESSION["CFD"] = $cfd;
		$newout = $data[4];
		$_SESSION["cfdFile"] = $newout;
		$_SESSION["imgpth"]=$data[5];
		$_SESSION["lineup"]=$data[6];
		$_SESSION["branch"]=$data[7];

		if($mode == "manual"){
			echo " true";
			echo "manualcfd";
		}else if($mode == "automatic"){
			echo " true";
			echo "automaticcfd";
		}

    }


?>
