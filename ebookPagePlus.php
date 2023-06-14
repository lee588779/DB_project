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

    //현재페이지 전체페이지 배열
    $arrayNowPage = array();
    $arrayBookPage = array();

    //독서현재진행비율 독서최고진행비율
    $arrayNowReading = array();
    $arrayTopReading = array();

    //현재페이지 검색
    $query1 = "SELECT * FROM `e북보관함` WHERE `도서번호` = '$bookNumber' AND `아이디`='$userid';";
    $res1 = mysqli_query($mysqli, $query1);

    while($row1 = mysqli_fetch_array($res1)){
        $arrayNowPage[] = $row1['현재페이지'];
        $arrayNowReading[] = $row1['독서현재진행비율'];
        $arrayTopReading[] = $row1['독서최고진행비율'];
    }

    //전체페이지 검색
    $query2 = "SELECT * FROM `도서` WHERE `도서번호` = '$bookNumber';";
    $res2 = mysqli_query($mysqli, $query2);

    while($row2 = mysqli_fetch_array($res2)){
        $arrayBookPage[] = $row2['전체페이지'];
    }

    $nowPage = 0;
    $nowPage = $arrayNowPage[0] + 1;

    $percent = round($nowPage/$arrayBookPage[0]*100);
    //페이지 증가
    if($nowPage > $arrayBookPage[0]){
        echo "<script>alert('마지막페이지입니다.')</script>";
        echo "<script>history.back();</script>";

    }else{
        $query3 = "UPDATE `e북보관함` SET `현재페이지` = '$nowPage' WHERE `도서번호`='$bookNumber' AND `아이디` = '$userid';";
        mysqli_query($mysqli, $query3);
        $query4 = "UPDATE `e북보관함` SET `독서현재진행비율` = '$percent' WHERE `도서번호`='$bookNumber' AND `아이디` = '$userid';";
        mysqli_query($mysqli, $query4);

        if($arrayTopReading[0] <= $percent){
            $query5 = "UPDATE `e북보관함` SET `독서최고진행비율` = '$percent' WHERE `도서번호`='$bookNumber' AND `아이디` = '$userid';";
            mysqli_query($mysqli, $query5);
        }
        echo "<script>history.back();</script>";
    }
?>