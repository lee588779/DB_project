<?php
   //이전 페이지
   $prevPage = $_SERVER['HTTP_REFERER'];
   
   header('loaction:'.$prevPage);
   
   include "./dbconn.php";
        
   $signup_id = $_GET['signup_id'];

   if(!$signup_id){
      echo "아이디를 입력하세요.";
      exit;
   }
    

	//데이터베이스 검색문
	$query = "SELECT * FROM 회원 WHERE 아이디='$signup_id'";
   $result=mysqli_query($mysqli, $query);
   $rowNum=mysqli_num_rows($result);

   //관리자 아이디,비밀번호
   $query5 = "SELECT `관리자아이디` FROM `관리자`;";

   $res5=mysqli_query($mysqli, $query5);

   $arrayManagerid = array();

   while($row5 = mysqli_fetch_array($res5)){
       $arrayManagerid[] = $row5['관리자아이디'];
   }

	if($rowNum!=0 OR $arrayManagerid[0]==$signup_id){
      
		echo "<script>alert('해당 아이디가 존재합니다.')</script>";
      echo "<script>history.back();</script>";
	}
		
	else{
      echo "<script>alert('사용가능한 아이디입니다.')</script>";
      echo "<script>history.back();</script>";
	}

   mysqli_close($mysqli);

?>