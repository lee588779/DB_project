<?php 
    
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];


    include "./dbconn.php";

    $passCode = $_GET['passCode'];

    $arrayPassCode = array();
    $arrayStudent = array();
    $arrayPassListCode = array();
    
    $arrayMonthPassNum= array();

    $arrayMonthPassCount = array();
    $arrayPassPrice = array();

    //회원의 패스코드 검색
    $query1 = "SELECT * FROM `회원` WHERE `아이디` = '$userid';";
    $res1 = mysqli_query($mysqli, $query1);

    while($row1 = mysqli_fetch_array($res1)){
        $arrayPassCode[] = $row1['패스코드'];
        $arrayStudent[] = $row1['대학생여부'];
    }

    //패스구매코드 생성하기
    $passListCode_query = "SELECT * FROM `패스구매내역` ORDER BY `패스구매코드` DESC";

    $res2 = mysqli_query($mysqli, $passListCode_query);


    //패스구매코드 생성
    while($row2 = mysqli_fetch_array($res2)){
        $arrayPassListCode[] = $row2['패스구매코드'];
    }

    $val1 = 0; 

    //시작일 마감일 생성
    $time_now = date("Y-m-d");
    $time_nowMonth = date("Y-m");
    $str_date = date("Y-m-d", strtotime($time_now.'+30 days'));


    //월패스통계번호 생성
    $query5 = "SELECT * FROM `월패스통계` ORDER BY `월패스통계번호` DESC";

    $res5 = mysqli_query($mysqli, $query5);

    while($row5 = mysqli_fetch_array($res5)){
        $arrayMonthPassNum[] = $row5['월패스통계번호'];
    }

    //월패스통계번호
    $MonthPassNum = 0;
    $MonthPassNum = $arrayMonthPassNum[0];

    
    if($arrayStudent[0]=='No' AND $passCode =='P-2'){

        echo "<script>alert('이것은 대학생전용패스입니다.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";

    }else{

        if($arrayPassCode[0] == 'P-0'){


            $val1 = $arrayPassListCode[0] + 1;

            $query2 = "UPDATE `회원` SET `패스코드` = '$passCode' WHERE  `아이디`='$userid';";
            mysqli_query($mysqli, $query2);

            $query3 = "INSERT INTO 패스구매내역(패스구매코드, 패스시작일, 패스마감일, 아이디, 패스코드) VALUES ('$val1', '$time_now', '$str_date', '$userid', '$passCode');";
            mysqli_query($mysqli, $query3);


            //월패스통계번호
            $MonthPassNum = $MonthPassNum + 1;


            //월패스통계 데이터 만들기
            $query5 = "SELECT * FROM `월패스통계` WHERE `월날짜`='$time_nowMonth' AND `패스코드` = '$passCode';";

            $res5 = mysqli_query($mysqli, $query5);

            $countMonth=mysqli_num_rows($res5);

            //월판매가 만들기
            $query8 = "SELECT count(*)  as '월판매수' FROM `패스구매내역` WHERE `패스코드`='$passCode';";

            $res8 = mysqli_query($mysqli, $query8);

            while($row8 = mysqli_fetch_array($res8)){
                $arrayMonthPassCount[] = $row8['월판매수'];
            }

            $query9 = "SELECT * FROM `패스` WHERE `패스코드`='$passCode';";

            $res9= mysqli_query($mysqli, $query9);

            while($row9 = mysqli_fetch_array($res9)){
                $arrayPassPrice[] = $row9['패스가격'];
            }

            $totalPassPrice = 0;
            $totalPassPrice = $arrayMonthPassCount[0]*$arrayPassPrice[0];

            if($countMonth==0){
                $query6 = "INSERT INTO 월패스통계(월패스통계번호, 월날짜, 월판매가, 패스코드) VALUES ('$MonthPassNum', '$time_nowMonth', '$totalPassPrice', '$passCode');";
                mysqli_query($mysqli, $query6);
            }else{
                $query7 = "UPDATE `월패스통계` SET `월판매가` = '$totalPassPrice' WHERE `월날짜`='$time_nowMonth' AND `패스코드` = '$passCode';";
                mysqli_query($mysqli, $query7);
            }
            
            echo "<script>alert('구매완료되었습니다.')</script>";
            echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
        
        }else{
            echo "<script>alert('이미 패스를 소지하고 계십니다.')</script>";
            echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php'</script>";
        }
    }

    
?>