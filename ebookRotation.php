<?php 
 
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

    $prevPage = $_SERVER['HTTP_REFERER'];

    header('loaction:'.$prevPage);

    include "./dbconn.php";

    $time_now = date("Y-m-d");

    $time_nowM = date("Y-m");

    $time_30 = date("Y-m-d", strtotime($time_now.'+30 days'));

    $str_date = date("Y-m-d", strtotime($time_now.'-5 years'));
    $str_date2 = date("Y-m-d", strtotime($time_now.'-1 months'));  //ex)현재가 12월 1일이면 11월 1일 출간된 책은 12월 1일까지는 신간으로 판정 그 안의 책들은 모두 신간취급        
        
        //e북로테이션
        $arrayEbookGenre = array();
        $ebookRotation = "";
        $arrayEbookNumber = array();
        $arrayPassStart = array();
        $arrayPassDead = array();
        $arrayEbookLocker = array();


        //e북 로테이션 장르코드 추출
        $query5 = "SELECT `장르코드`, sum(`도서조회횟수`) as '도서조회횟수' from `도서조회이력` WHERE `아이디`= '$userid' AND DATE_FORMAT(`도서최종조회날짜`,'%Y-%m') = '$time_nowM' AND `장르코드` != 'G-3' AND `장르코드` != 'G-5' GROUP BY `장르코드` ORDER BY 도서조회횟수 DESC;";
        $res5 = mysqli_query($mysqli, $query5);

        while($row5 = mysqli_fetch_array($res5)){
            $arrayEbookGenre[] = $row5['장르코드'];
        }

        $query11 = "SELECT * from`e북보관함` WHERE `아이디`='$userid';";
        $res11 = mysqli_query($mysqli, $query11);

        while($row11 = mysqli_fetch_array($res11)){
            $arrayEbookLocker[] = $row11['도서번호'];
        }
    

        //장르코드 없고 e북보관함도 없을때
        if(count($arrayEbookLocker)==0 AND count($arrayEbookGenre)==0){
            $query10 = "SELECT * from `도서` WHERE DATE_FORMAT(`출간일`, '%Y-%m-%d')>='$str_date' AND DATE_FORMAT(`출간일`, '%Y-%m-%d')<='$str_date2' AND `장르코드` != 'G-3' AND `장르코드` != 'G-5' Order by rand() Limit 1;";
            $res10 = mysqli_query($mysqli, $query10);

            while($row10 = mysqli_fetch_array($res10)){
                $arrayEbookNumber[] = $row10['도서번호'];
            }
            $ebookRotation = $arrayEbookNumber[0];
        }

        //장르코드 없고 e북보관함 있을때
        if(count($arrayEbookLocker)!=0 AND count($arrayEbookGenre)==0){
            $query12 = "SELECT * from `도서` WHERE DATE_FORMAT(`출간일`, '%Y-%m-%d')>='$str_date' AND DATE_FORMAT(`출간일`, '%Y-%m-%d')<='$str_date2' AND `장르코드` != 'G-3' AND `장르코드` != 'G-5' Order by rand() Limit 1;";
            $res12 = mysqli_query($mysqli, $query12);

            while($row12 = mysqli_fetch_array($res12)){
                $arrayEbookNumber[] = $row12['도서번호'];
            }
            $ebookRotation = $arrayEbookNumber[0];

        }else{
            for($i=0;$i<count($arrayEbookGenre);$i++){
                    $ebookRotation = $arrayEbookGenre[0];

                    //e북 로테이션 도서 추출(현재 해부터 5년안)
                    $query6 = "SELECT * from `도서` WHERE `장르코드`= '$ebookRotation'  AND DATE_FORMAT(`출간일`, '%Y-%m-%d')>='$str_date' AND DATE_FORMAT(`출간일`, '%Y-%m-%d')<='$str_date2' Order by rand() Limit 1;";
                    $res6 = mysqli_query($mysqli, $query6);

                    while($row6 = mysqli_fetch_array($res6)){
                        $arrayEbookNumber[] = $row6['도서번호'];
                    }
                    if(!in_array($arrayEbookNumber[0], $arrayEbookLocker)){
                        break;
                    }
            }
    
        }

        //e북로테이션에 로테이션이 존재하는지 찾기
        $query7 = "SELECT * from `e북로테이션` WHERE `아이디`='$userid' ORDER BY `e북로테이션시작일` DESC;";
        $res7 = mysqli_query($mysqli, $query7);

        while($row7 = mysqli_fetch_array($res7)){
            $arrayPassStart[] = $row7['e북로테이션시작일'];
            $arrayPassDead[] = $row7['e북로테이션마감일'];
        }

        $count = count($arrayPassDead);
        if($count==0 OR $arrayPassDead[0]<$time_now){
            //신규회원 OR e북로테이션마감일<현재날짜
            $query8 = "INSERT INTO `e북로테이션`(`아이디`, `도서번호`, `e북로테이션시작일`, `e북로테이션마감일`, `열람여부`) VALUES ('$userid','$arrayEbookNumber[0]','$time_now','$time_30','0');";
            mysqli_query($mysqli, $query8);
            $query9 = "INSERT INTO `e북보관함`(`아이디`, `도서번호`, `현재페이지`, `열람마지막날짜`, `소장/패스/로테이션`, `마감기간`, `독서현재진행비율`, `독서최고진행비율`) VALUES ('$userid','$arrayEbookNumber[0]', '0', '0', '로테이션', '$time_30', '0', '0');";
            mysqli_query($mysqli, $query9);

            echo "<script>alert('발급되었습니다.');history.back();</script>";
            echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php';</script>";

        }if($arrayPassDead[0]>=$time_now){
            echo "<script>alert('이미 사용중인 로테이션이 있습니다.');history.back();</script>";
            echo "<script>location.href='http://bookdatabase.dothome.co.kr/main.php';</script>";
        }
?>