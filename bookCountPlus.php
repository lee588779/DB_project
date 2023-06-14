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

    $bookCount = $_GET['bookCount'];

    if(SUBSTR($bookNumber,0,1)=='b'){
        //재고량
        $inventory_query = "SELECT * FROM `도서` WHERE 도서번호 = '$bookNumber';";
        $inventory_res = mysqli_query($mysqli, $inventory_query);

        $arrayInventory = array();

        while($inventory_row = mysqli_fetch_array($inventory_res)){
            $arrayInventory[] = $inventory_row['재고량'];
        }


        if($bookCount < $arrayInventory[0]){
                
            //장바구니번호로 장바구니항목검색
            $query1 = "SELECT * FROM `장바구니항목` WHERE 장바구니번호 = '$basketNumber';";
            $res1 = mysqli_query($mysqli, $query1);

            $arrayBasketNumber = array();
            $arrayBookCount = array();
                                
            while($row1 = mysqli_fetch_array($res1)){
                $arrayBasketNumber[] = $row1['장바구니번호'];
                $arrayBookCount[] = $row1['수량'];
            }
                
            $plusCount = $arrayBookCount[0] + 1;
            $abn = $arrayBasketNumber[0];

            $query2 = "UPDATE `장바구니항목` SET `수량` = '$plusCount' WHERE  `장바구니번호`='$abn';";
            $res2 = mysqli_query($mysqli, $query2);
            echo "<script>history.back();</script>";
        }
        else{
            echo "<script>alert('재고량을 초과하셨습니다.')</script>";
            echo "<script>history.back();</script>";
        }
    }else{
        echo "<script>alert('e북은 수량을 조절할 수 없습니다.')</script>";
        echo "<script>history.back();</script>";
    }

    mysqli_close($mysqli);
    
?>