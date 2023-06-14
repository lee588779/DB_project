<?php

    $prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);
    header("Content-Type: text/html;charset=UTF-8");
	
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

	$prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);

    include "./dbconn.php";

    $bookNumber = $_GET['bookNumber'];
    $basketNumber = $_GET['basketNumber'];


    if(SUBSTR($bookNumber,0,1)=='b'){
            $query1 = "SELECT * FROM `장바구니항목` WHERE 장바구니번호 = '$basketNumber';";
            $res1 = mysqli_query($mysqli, $query1);

            $arrayBasketNumber = array();
            $arrayBookCount = array();
                            
            while($row1 = mysqli_fetch_array($res1)){
                $arrayBasketNumber[] = $row1['장바구니번호'];
                $arrayBookCount[] = $row1['수량'];
            }
        if($arrayBookCount[0]>0){
            $minusCount = $arrayBookCount[0] - 1;
            $abn = $arrayBasketNumber[0];

            if($minusCount==0){
                $query="UPDATE `장바구니항목` SET `수량` = '1' WHERE  `장바구니번호`='$abn';";
                $res = mysqli_query($mysqli, $query);
                echo "<script>alert('삭제시켜주세요.')</script>";
                echo "<script>history.back();</script>";
            }else{
                $query2 = "UPDATE `장바구니항목` SET `수량` = '$minusCount' WHERE  `장바구니번호`='$abn';";
                $res2 = mysqli_query($mysqli, $query2);

            echo "<script>history.back();</script>";
            }
        }
        else{
            echo "<script>history.back();</script>";
        }
    }else{
        echo "<script>alert('e북은 수량을 조절할 수 없습니다.')</script>";
        echo "<script>history.back();</script>";
    }

    mysqli_close($mysqli);
?>