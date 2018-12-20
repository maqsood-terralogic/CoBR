<?php
    session_start();
    $posted_data = $_POST['json'];
    $data = json_decode($posted_data,true);
    var_dump($data);
    $fromPage = $data[0];
    if($fromPage == "bugdetails"){
        echo "bugdetails";
        $_SESSION["bugids"] = $data[1];
    }else if($fromPage == "impactedSource"){
        echo "impactedSource";
        $_SESSION["bugids"] = $data[1];
        $_SESSION["impactedfiles"] = $data[2];
    }else if($fromPage == "coveragePage"){
        echo "coveragePage";
        $_SESSION["testcases"] = $data[1];
    }else if($fromPage == "utims"){
        echo "utims";
        $_SESSION["testSuite"] = $data[1];
		$_SESSION["testcases_option"] = $data[2];
    }else if($fromPage == "utimsautomatic"){
        echo "utimsautomatic";
        $_SESSION["testSuite"] = $data[1];
		$_SESSION["testcases_option"] = $data[2];
    }
?>
