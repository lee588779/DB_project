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



    $OrderingNumber=$_GET['OrderingNumber']; //주문번호
    $OrderingDeatil=$_GET['OrderingDeatil']; //상세정보
    $OrderingPrice=$_GET['OrderingPrice'];  //주문총액
    $deliverNo=$_GET['deliverNo'];          //반품사유

    
    $arrayOrderListCode = array();
    $arrayOrderCount = array();
    $arrayBookNumber = array();
    $arrayBookCount = array();         //도서 재고량

    $arrayRefund = array();            //환불총액
    $arrayUsePoint = array();          //적립금 사용액
    $arrayPoint = array();             //적립금

    $arrayBookPrice = array();         //e북 판매가
    $arrayEbookCount = array();        //e북 재고량

    $refundPirce = 0;


        //주문항목의 주문번호 검색
        $query4 = "SELECT * FROM `주문항목` WHERE `주문번호`='$OrderingNumber';";

        $res4 = mysqli_query($mysqli, $query4);

        //적립금 환불
        $query7 = "SELECT * FROM 주문 WHERE 아이디='$userid' and 주문번호='$OrderingNumber';";

        $res7 = mysqli_query($mysqli, $query7);

        $query8 = "SELECT * FROM 회원 WHERE 아이디='$userid';";

        $res8 = mysqli_query($mysqli, $query8);

        while($row7 = mysqli_fetch_array($res7)){
            $arrayRefund[] = $row7['주문총액'];
            $arrayUsePoint[] = $row7['적립금사용액'];
        }


        while($row8 = mysqli_fetch_array($res8)){
            $arrayPoint[] = $row8['적립금'];
        }
        
        
        while($row4 = mysqli_fetch_array($res4)){
            $arrayBookNumber[] = $row4['도서번호'];
        }

        //환불된 적립금 계산
        $totalRefundPoint = $arrayPoint[0] - $arrayRefund[0]*0.1 + $arrayUsePoint[0];

        //도서번호로 도서찾기
        for($j=0;$j<count($arrayBookNumber);$j++){

            if(SUBSTR($arrayBookNumber[$j],0,1)=='b'){
                    $query6 = "SELECT * FROM `도서` WHERE `도서번호`='$arrayBookNumber[$j]';";

                    $res6 = mysqli_query($mysqli, $query6);

                    $query11 = "SELECT * FROM `주문항목` WHERE `도서번호`='$arrayBookNumber[$j]' and `주문번호`='$OrderingNumber';";

                    $res11 = mysqli_query($mysqli, $query11);

                    while($row6 = mysqli_fetch_array($res6)){
                        $arrayBookCount[] = $row6['재고량'];
                    }

                    while($row11 = mysqli_fetch_array($res11)){
                        $arrayOrderListCode[] = $row11['주문항목코드'];
                        $arrayOrderCount[] = $row11['수량'];
                    }
            }else{
                $query9 = "SELECT * FROM `도서` WHERE `도서번호`='$arrayBookNumber[$j]';";

                $res9 = mysqli_query($mysqli, $query9);

                $query12 = "SELECT * FROM `주문항목` WHERE `도서번호`='$arrayBookNumber[$j]' and `주문번호`='$OrderingNumber';";

                $res12 = mysqli_query($mysqli, $query12);

                while($row9 = mysqli_fetch_array($res9)){
                    $arrayBookPrice[] = $row9['판매가'];
                }

                while($row12 = mysqli_fetch_array($res12)){
                    $arrayEbookCount[] = $row12['수량'];
                }

            }
        }

        //환불총액 계산
        $refundPirce = $arrayRefund[0] + $arrayUsePoint[0];
        for($y=0; $y < count($arrayBookPrice); $y++){
            $refundPirce = $refundPirce - $arrayBookPrice[$y]*$arrayEbookCount[$y];
        }

        
        $time_now = date("Y-m-d");
        $str_date = date("Y-m-d", strtotime($time_now.'+3 days'));


        if($Ordering=="배송완료"){
            $query2 = "UPDATE `주문` SET `주문상태`='취소',`반품사유`='$deliverNo', `환불일자` = '$str_date', `환불총액`='$refundPirce' WHERE `주문번호`='$OrderingNumber';";
            $res2 = mysqli_query($mysqli, $query2);

            $query10 = "UPDATE `회원` SET `적립금`='$totalRefundPoint' WHERE `아이디`='$userid';";
            mysqli_query($mysqli, $query10);

            for($i=0; $i<count($arrayOrderListCode);$i++){
                $query5="UPDATE `도서` SET `재고량` = '$arrayOrderCount[$i]'+'$arrayBookCount[$i]' WHERE  `도서번호` IN (SELECT `도서번호` FROM `주문항목` WHERE `주문항목코드` = '$arrayOrderListCode[$i]');";
                mysqli_query($mysqli, $query5);
            }


        }
        else{
            $query3 = "UPDATE `주문` SET `주문상태`='취소',`반품사유`='$deliverNo', `환불일자` = '$str_date', `환불총액`='$refundPirce' WHERE `주문번호`='$OrderingNumber';";
            $res3 = mysqli_query($mysqli, $query3);

            $query13 = "UPDATE `회원` SET `적립금`='$totalRefundPoint' WHERE `아이디`='$userid';";
            mysqli_query($mysqli, $query13);

            for($i=0; $i<count($arrayOrderListCode);$i++){
                $query14="UPDATE `도서` SET `재고량` = '$arrayOrderCount[$i]'+'$arrayBookCount[$i]' WHERE  `도서번호` IN (SELECT `도서번호` FROM `주문항목` WHERE `주문항목코드` = '$arrayOrderListCode[$i]');";
                mysqli_query($mysqli, $query14);
            }
        }
        

        echo "<script>alert('환불되었습니다.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
?>