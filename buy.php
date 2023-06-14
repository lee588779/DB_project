<?php
    $prevPage = $_SERVER['HTTP_REFERER'];
    
    header('loaction:'.$prevPage);
    header("Content-Type: text/html;charset=UTF-8");
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];
    
    include "./dbconn.php";


    $bookPoint = $_GET['bookPoint'];

    $time_now = date("Y-m-d");
    $time_nowMonth = date("Y-m");


    $address_query = "SELECT * FROM `배송지` WHERE 아이디 = '$userid';";
    $card_query = "SELECT * FROM `신용카드정보` WHERE 아이디 = '$userid';";

    $address_res = mysqli_query($mysqli, $address_query);        
    $card_res = mysqli_query($mysqli, $card_query);

    //배송지 신용카드정보
    $arrayAddress = array();
    $arrayCardinfo = array();

    //배송지 정보 배열로 담기
    while($address_row = mysqli_fetch_array($address_res)){
        $arrayAddress[] = $address_row['배송지'];
    }
    //신용카드 정보 배열로 담기
    while($card_row = mysqli_fetch_array($card_res)){
        $arrayCardinfo[] = $card_row['신용카드정보'];
    }
    


    //주문번호 만들기

    $order_query = "SELECT * FROM `주문` ORDER BY 주문번호 DESC";

    $orderres = mysqli_query($mysqli, $order_query);

    $arrayOrderNumber = array();

    //주문번호 생성
    while($orderrow = mysqli_fetch_array($orderres)){
        $arrayOrderNumber[] = $orderrow['주문번호'];
    }

    //주문항목코드 만들기

    $bookListCode_query = "SELECT * FROM `주문항목` ORDER BY 주문항목코드 DESC";

    $bookListCoderes = mysqli_query($mysqli, $bookListCode_query);

    $arraybookListCode = array();

    //주문항목코드 생성
    while($bookListCoderow = mysqli_fetch_array($bookListCoderes)){
        $arraybookListCode[] = $bookListCoderow['주문항목코드'];
    }

    //장바구니 아이디 검색문
    $query1 = "SELECT * FROM 장바구니 WHERE 아이디='$userid';";
                
    $res1 = mysqli_query($mysqli, $query1);

    //장바구니번호, 도서번호, 수량
    $arrayBasketNumber = array();
    $arrayBookNumber = array();
    $arrayBookCount = array();

    //도서명,저자,출판사,판매가
    $arrayBookName = array();
    $arrayBookAuthor = array();
    $arrayBookPublish = array();
    $arrayBookPrice = array();
    $arrayInventory = array();

    //적립금
    $arrayBookPonint=array();

    //총액
    $totalPrice = 0;

    //상세정보
    $detailInfo = "";
    
    //재고량
    $totalInventory = array();

    //주문항목 도서번호
    $aban = " ";

    //e북도서
    $arrayEbookNumber = array();

    //소장한 e북 찾기
    $arrayEbookNum = array();

    //도서조회이력 조회횟수
    $arrayLookCount = array();

    //도서 장르코드
    $arrayEbookGenre = array();
    
    //도서조회이력번호
    $arrayBookLookCountNum = array();

    //도서월판매량
    $totalBookCount = 0;
    $totaleBookCount = 0;
    $arrayMonth = array();
    $arrayBookMonthsold = array();

    //월주문소장통계
    $countMonthBook = array();
    $countMonthEbook = array();

    //회원등급변경
    $arrayuserRating = array();
    $arrayOrderPrice = array();
    $arrayOrderRefund = array();




    //장바구니번호 배열 넣기
    while($row1 = mysqli_fetch_array($res1)){
        $arrayBasketNumber[] = $row1['장바구니번호'];
    }
    //장바구니번호로 장바구니항목에서 추출
    for($i=0; $i < count($arrayBasketNumber); $i++){
        $j=$arrayBasketNumber[$i];
        $query2 = "SELECT * FROM `장바구니항목` WHERE `장바구니번호`='$j'";

        $res2 = mysqli_query($mysqli, $query2);

        //추출한 장바구니 번호로 도서번호와 수량 추출
        while($row2 = mysqli_fetch_array($res2)){
            $arrayBookNumber[] = $row2['도서번호'];
            $arrayBookCount[] = $row2['수량'];
        }
    }

    //도서번호로 도서추출
    for($k=0; $k < count($arrayBookNumber); $k++){
        $l=$arrayBookNumber[$k];
        $query3 = "SELECT * FROM 도서 WHERE 도서번호='$l'";

        $res3 = mysqli_query($mysqli, $query3);

        while($row3 = mysqli_fetch_array($res3)){
            $arrayBookName[] = $row3['도서명'];
            $arrayBookAuthor[] = $row3['저자'];
            $arrayBookPublish[] = $row3['출판사'];
            $arrayBookPrice[] = $row3['판매가'];
            $arrayInventory[] = $row3['재고량'];
        }
    }
    

    //상세정보 만들기
    for($s=0; $s<count($arrayBasketNumber); $s++){
        $bn=$arrayBookName[$s];          //도서명
        $bx=$arrayBookCount[$s];         //x
        $bk="권, ";                      //권
        $space = " ";
        $mix = $bn.$space.$bx.$bk;              //위의 4개 합체
        $detailInfo = $detailInfo.$mix;

        $bi = $arrayInventory[$s];      //재고량
        if(SUBSTR($arrayBookNumber[$s],0,1)=='b'){
            $totalInventory[$s] = $bi - $bx;
        }else{
            $totalInventory[$s] = $arrayInventory[$s];

        }
    }

    //회원 적립금 추출
    $query8 = "SELECT * FROM `회원` WHERE `아이디`='$userid';";

    $res8 = mysqli_query($mysqli, $query8);

    while($row8 = mysqli_fetch_array($res8)){
        $arrayBookPonint[] = $row8['적립금'];
    }

    //적립금
    $totalPoint = 0;

    $div = 1000;
    
    if(!$bookPoint){
        $bookPoint = 0;
    }
    else{
        $pointdiv = $bookPoint%$div;
    }

        //총액 계산문
        if($pointdiv==0 && $bookPoint<=$arrayBookPonint[0]){
            for($t=0; $t<count($arrayBasketNumber);$t++){
                $price = 0;
                $price = $arrayBookPrice[$t]*$arrayBookCount[$t];
                $totalPrice = $price + $totalPrice; 
            }
            $totalPrice = $totalPrice - $bookPoint;
            $totalPoint = ($arrayBookPonint[0] - $bookPoint)+($totalPrice*0.1);
        }
        else{
            for($t=0; $t<count($arrayBasketNumber);$t++){
                $price = 0;
                $price = $arrayBookPrice[$t]*$arrayBookCount[$t];
                $totalPrice = $price + $totalPrice; 
            }
            $totalPrice = $totalPrice - $bookPoint;
            $totalPoint = ($arrayBookPonint[0] - $bookPoint)+($totalPrice*0.1);
        }

    //도서조회이력번호 생성
    $query7 = "SELECT * FROM `도서조회이력` ORDER BY `도서조회이력번호` DESC";

    $res7 = mysqli_query($mysqli, $query7);

    while($row7 = mysqli_fetch_array($res7)){
        $arrayBookLookCountNum[] = $row7['도서조회이력번호'];
    }

    //도서조회이력번호
    $bookCountLookNum = 0;
    $bookCountLookNum = $arrayBookLookCountNum[0];


    //도서월판매량번호 생성
    $query18 = "SELECT * FROM `도서월판매량` ORDER BY `도서월판매량번호` DESC";

    $res18 = mysqli_query($mysqli, $query18);

    while($row18 = mysqli_fetch_array($res18)){
        $arrayBookMonthsold[] = $row7['도서월판매량번호'];
    }

    //도서월판매량번호
    $arrayBookMonthsoldNum = 0;
    $arrayBookMonthsoldNum = $arrayBookMonthsold[0];


    //소장한 e북 찾기
    $query4 = "SELECT * FROM `e북보관함` WHERE `아이디`='$userid' AND `소장/패스/로테이션` = '소장';";
                
    $res4 = mysqli_query($mysqli, $query4);

    while($row4 = mysqli_fetch_array($res4)){
        $arrayEbookNum[] = $row4['도서번호'];
    }

    //소장한 책이 있을시 1로 변경
    $ebookval = 0;
    for($f=0;$f<count($arrayBookNumber);$f++){
        if(count($arrayEbookNum)==0){
            $ebookval = 0;
        }
        if(in_array($arrayBookNumber[$f], $arrayEbookNum)){
            $ebookval = 1;
        }
    }
    if($ebookval==1){
        echo "<script>alert('이미 소장한 책입니다.')</script>";
        echo "<script>history.back();</script>";
    }else{
        if(in_array('0', $arrayInventory)){
            echo "<script>alert('재고가 없습니다.')</script>";
            echo "<script>history.back();</script>";
        }
        else{
            if(count($arrayAddress)!=0 && count($arrayCardinfo)!= 0){
                
                if(!$arrayBasketNumber[0]){
                    echo "<script>alert('도서를 장바구니에 담아 주세요.')</script>";
                    echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
                }
                    
                else{
                    //적립금
                    if($pointdiv==0 && $bookPoint<=$arrayBookPonint[0]){
                        //주문번호
                        $valNumber = $arrayOrderNumber[0] + 1;
                        
                        $query = "INSERT INTO 주문(주문번호, 상세정보, 주문총액, 주문일자, 아이디, 주문상태, 반품사유, 환불일자, 환불총액, `적립금사용액`) VALUES ('$valNumber', '$detailInfo', '$totalPrice', DATE_FORMAT(now(), '%Y-%m-%d'), '$userid', '준비중', '0', '0', '0', '$bookPoint')";
                        
                        mysqli_query($mysqli, $query);

                
                        //주문항목코드
                        if($arraybookListCode[0]<5000){
                            $arraybookListCode[0] = 4999;
                        }

                        for($a=0; $a < count($arrayBasketNumber);$a++){
                        
                            $aban = $arrayBookNumber[$a];
                        
                            $ti = $totalInventory[$a];
                        
                            $orderItemListCode = $arraybookListCode[0] + 1;        
                        
                            if($orderItemListCode>=5000){
                                    $bc = $arrayBookCount[$a];
                                    
                                    $query5 = "INSERT INTO `주문항목`(`도서번호`, `주문번호`, `주문항목코드`, `수량`, `반품수량`, `주문항목생성일자`) VALUES ('$aban','$valNumber', '$orderItemListCode', '$bc', '0', '$time_now');";
                            
                                    mysqli_query($mysqli, $query5);


                                $query6 = "UPDATE `도서` SET `재고량` = '$ti' WHERE `도서번호`='$aban';";
                            
                                mysqli_query($mysqli, $query6);

                                $arraybookListCode[0]=$orderItemListCode; 

                            }
                        }
                        $bookPoint_query = "UPDATE `회원` SET `적립금` = '$totalPoint' WHERE `아이디`='$userid';";
                        mysqli_query($mysqli, $bookPoint_query);

                        for($e=0;$e<count($arrayBookNumber);$e++){

                            if(SUBSTR($arrayBookNumber[$e],0,1)=='e'){

                                $query10 = "SELECT * FROM `e북보관함` WHERE `도서번호`='$arrayBookNumber[$e]' AND `아이디`='$userid';";

                                $res10 = mysqli_query($mysqli, $query10);

                                while($row10 = mysqli_fetch_array($res10)){
                                    $arrayEbookNumber[] = $row10['도서번호'];
                                }
                                if($arrayEbookNumber[0]==$arrayBookNumber[$e]){
                                    $query11 = "UPDATE `e북보관함` SET `소장/패스/로테이션` = '소장', `마감기간` = '없음' WHERE `도서번호`='$arrayBookNumber[$e]' AND `아이디`='$userid';";
                            
                                    mysqli_query($mysqli, $query11);
                                }else{
                                    $query9 = "INSERT INTO `e북보관함`(`아이디`, `도서번호`, `현재페이지`, `열람마지막날짜`, `소장/패스/로테이션`, `마감기간`, `독서현재진행비율`, `독서최고진행비율`) VALUES ('$userid','$arrayBookNumber[$e]', '0', '0', '소장', '없음', '0', '0');";
                            
                                    mysqli_query($mysqli, $query9);
                                }
                                
                            }
                        }
                        

                        //도서번호별 총 판매량 가져오기
                        for($y=0;$y<count($arrayBasketNumber);$y++){

                            $totalMonthCount=0;

                            $arrayTotalCount = array();
                            $arraybookMonthNum = array();
                            $arrayRefundTotalCount = array();

                            $query19 = "SELECT `도서번호`, sum(`수량`) as '판매량', sum(`반품수량`) as '반품수량' FROM `주문항목` WHERE DATE_FORMAT(`주문항목생성일자`, '%Y-%m')='$time_nowMonth' AND `도서번호` = '$arrayBookNumber[$y]';";

                            $res19 = mysqli_query($mysqli, $query19);

                            while($row19 = mysqli_fetch_array($res19)){
                                $arraybookMonthNum[] = $row19['도서번호'];
                                $arrayTotalCount[] = $row19['판매량'];
                                $arrayRefundTotalCount[] = $row19['반품수량'];
                            }

                            $arrayBookMonthsoldNum = $arrayBookMonthsoldNum + 1;

                            $totalMonthCount = $arrayTotalCount[0] - $arrayRefundTotalCount[0];

                            $query17 = "INSERT INTO `도서월판매량`(`도서월판매량번호`, `월날짜`, `판매량`, `도서번호`) VALUES ('$arrayBookMonthsoldNum', '$time_nowMonth', '$totalMonthCount', '$arrayBookNumber[$y]');";
    
                            mysqli_query($mysqli, $query17);

                            $query20 = "UPDATE `도서월판매량` SET `판매량` = '$totalMonthCount' WHERE `월날짜`='$time_nowMonth' AND `도서번호` = '$arrayBookNumber[$y]';";
                            mysqli_query($mysqli, $query20);


                            

                        }

                        

                        for($z=0; $z < count($arrayBookNumber); $z++){

                            //도서조회이력번호
                            $bookCountLookNum = $bookCountLookNum + 1;

                            //구매시 도서조회이력 업데이트
                            $query12 = "SELECT * FROM `도서조회이력` WHERE `도서번호`='$arrayBookNumber[$z]' AND `아이디`='$userid';";

                            $res12 = mysqli_query($mysqli, $query12);

                            while($row12 = mysqli_fetch_array($res12)){
                                $arrayLookCount[] = $row12['도서조회횟수'];
                            }

                            $query15 = "SELECT * FROM `도서` WHERE `도서번호`='$arrayBookNumber[$z]';";

                            $res15 = mysqli_query($mysqli, $query15);

                            while($row15 = mysqli_fetch_array($res15)){
                                $arrayEbookGenre[] = $row15['장르코드'];
                            }

                            //조회횟수 추가
                            $LookCount = 0;
                            $LookCount = $arrayLookCount[0] + 1;

                            if(count($arrayLookCount)==0){
                                $query13 = "INSERT INTO `도서조회이력`(`도서조회이력번호`, `도서최종조회날짜`, `도서조회횟수`, `도서번호`, `아이디`, `장르코드`) VALUES ('$bookCountLookNum', '$time_now', 1, '$arrayBookNumber[$z]', '$userid', '$arrayEbookGenre[0]');";
                                mysqli_query($mysqli, $query13);

                            }else{
                                $query14 = "UPDATE `도서조회이력` SET `도서조회횟수` = '$LookCount', `도서최종조회날짜` = '$time_now' WHERE `도서번호`='$arrayBookNumber[$z]' AND `아이디`='$userid';";
                                mysqli_query($mysqli, $query14);
                            }

                        }

                        //월주문소장통계
                        
                        //도서판매량
                        $query23 = "SELECT sum(`판매량`) as '판매량' FROM `도서월판매량` WHERE SUBSTR(`도서번호`,1,1)='b' AND `월날짜`='$time_nowMonth';";

                        $res23 = mysqli_query($mysqli, $query23);

                        while($row23 = mysqli_fetch_array($res23)){
                            $countMonthBook[] = $row23['판매량'];
                        }

                        //e북판매량
                        $query24 = "SELECT sum(`판매량`) as '판매량' FROM `도서월판매량` WHERE SUBSTR(`도서번호`,1,1)='e' AND `월날짜`='$time_nowMonth';";

                        $res24 = mysqli_query($mysqli, $query24);

                        while($row24 = mysqli_fetch_array($res24)){
                            $countMonthEbook[] = $row24['판매량'];
                        }

                        //월날짜 검색
                        $query25 = "SELECT * FROM `월주문소장통계` WHERE `월날짜`='$time_nowMonth';";

                        $res25 = mysqli_query($mysqli, $query25);

                        $countMonth=mysqli_num_rows($res25);


                        if($countMonth==0){
                            $query21 = "INSERT INTO `월주문소장통계`(`월날짜`, `e북판매량`, `도서판매량`) VALUES ('$time_nowMonth', '$countMonthEbook[0]', '$countMonthBook[0]');";

                            mysqli_query($mysqli, $query21);
                        }
                        
                        else{
                            $query22 = "UPDATE `월주문소장통계` SET `e북판매량` = '$countMonthEbook[0]', `도서판매량` = '$countMonthBook[0]' WHERE `월날짜`='$time_nowMonth';";
                            mysqli_query($mysqli, $query22);
                        }

                        //회원등급변경
                        $query26 = "SELECT sum(`주문총액`) as '주문총액' FROM `주문` WHERE `아이디` = '$userid';";
                        $res26 = mysqli_query($mysqli, $query26);

                        
                        while($row26 = mysqli_fetch_array($res26)){
                            $arrayOrderPrice[] = $row26['주문총액'];
                        }

                        $query28 = "SELECT sum(`환불총액`) as '환불총액' FROM `주문` WHERE `아이디` = '$userid';";
                        $res28 = mysqli_query($mysqli, $query28);

                        while($row28 = mysqli_fetch_array($res28)){
                            $arrayOrderRefund[] = $row28['환불총액'];
                        }


                        $totalOrderPrice = 0;
                        $totalOrder = 0;

                        for($b=0;$b<count($arrayOrderPrice);$b++){
                            if(count($arrayOrderRefund)==0){
                                $arrayOrderRefund[$b] = 0;
                            }
                            $totalOrder = 0;
                            $totalOrder = $arrayOrderPrice[$b]-$arrayOrderRefund[$b];
                            $totalOrderPrice = $totalOrderPrice + $totalOrder;
                        }

                        if($totalOrder>=300000){
                            $query27 = "UPDATE `회원` SET `등급코드`='R-2' WHERE `아이디`='$userid';";
                            mysqli_query($mysqli, $query27);
                        }if($totalOrder>=1000000){
                            $query27 = "UPDATE `회원` SET `등급코드`='R-3' WHERE `아이디`='$userid';";
                            mysqli_query($mysqli, $query27);
                        }
                        
                        echo "<script>alert('구매가 완료되었습니다.')</script>";
                        echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
                    }
                    else{
                        echo "<script>alert('적립금은 1000단위로 사용해주세요.')</script>";
                        echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
                    }
                    
                }
            }
            else{
                echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
            }
        }
    }




    

    mysqli_close($mysqli);
?>