<?php
	session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

	$prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);

    header("Content-Type: text/html;charset=UTF-8");
    
    include "./dbconn.php";

    $time_now = date("Y-m-d");
    
    $bookNumber=$_GET['bookNumber']; //도서번호
        
    //데이터베이스 삽입문
    if(!$userid){
		echo "<script>alert('로그인해주세요.')</script>";
        echo "<script>history.back();</script>";
    }else{
        
        $select_query = "SELECT * FROM `장바구니` ORDER BY 장바구니번호 DESC";
        
        $select_query2 = "SELECT `도서번호` FROM `장바구니항목` WHERE `장바구니번호` IN (SELECT `장바구니번호` FROM `장바구니` WHERE `아이디`= '$userid');";

        //장바구니검색
        $res=mysqli_query($mysqli, $select_query);
        
        //도서번호검색
        $res2=mysqli_query($mysqli, $select_query2);


        $arrayBasketNumber = array();
        $arrayBookNumber = array();

        //장바구니번호 생성
        while($row = mysqli_fetch_array($res)){
            $arrayBasketNumber[] = $row['장바구니번호'];
        }

        while($row2 = mysqli_fetch_array($res2)){
            $arrayBookNumber[] = $row2['도서번호'];
        }


        $val1 = $arrayBasketNumber[0] + 1;


        $j=0;
        
        for($i=0; $i < count($arrayBookNumber); $i++){
            if($bookNumber==$arrayBookNumber[$i]){
                echo "<script>alert('이미 담은 도서입니다.')</script>";
                echo "<script>history.back();</script>";
                $j=1;
                break;
            }
        }
        if($j==0){
            $insert_query1 = "INSERT INTO 장바구니(장바구니번호, 생성일자, 아이디) VALUES ('$val1', '$time_now', '$userid')";
            mysqli_query($mysqli, $insert_query1);
            $insert_query2 = "INSERT INTO 장바구니항목(장바구니번호, 도서번호, 수량) VALUES ('$val1', '$bookNumber', '1')";
            mysqli_query($mysqli, $insert_query2);

            echo "<script>alert('장바구니에 추가되었습니다.')</script>";
            echo "<script>history.back();</script>";
        }
        }
        
?>