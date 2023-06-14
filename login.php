<?php 
 
    $prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);
    
    include "./dbconn.php";
        
    $login_id = $_GET['login_id'];
    $login_pw = $_GET['login_pw'];


    $time_now = date("Y-m-d");

    //관리자 아이디,비밀번호
    $query5 = "SELECT `관리자아이디` FROM `관리자`;";

    $res5=mysqli_query($mysqli, $query5);

    $arrayManagerid = array();
    $arrayManagerName = array();

    while($row5 = mysqli_fetch_array($res5)){
        $arrayManagerid[] = $row5['관리자아이디'];
    }


    if(!$login_id){
        // 경고창 보여주고 이전 페이지로 이동 [JS의 history객체 이동]
        // history.go(-1); : 이전 페이지로
        echo "<script>alert('아이디를 입력하세요.');</;script>";
        echo "<script>history.back();</script>";
    }
    if(!$login_pw){
        // 경고창 보여주고 이전 페이지로 이동 [JS의 history객체 이동]
        // history.back(); : 이전 페이지로
        echo "<script>alert('비밀번호를 입력하세요.');</script>";
        echo "<script>history.back();</script>";

    }if($login_id==$arrayManagerid[0]){
        //데이터베이스 검색문        
        $query = "SELECT * FROM 관리자 WHERE 관리자아이디='$login_id' and 관리자비밀번호='$login_pw'";

        $res=mysqli_query($mysqli, $query);
        $rowNum=mysqli_num_rows($res);

        // $rowNum이 0이면 아이디와 패스워드가 맞지 않는 것
        if(!$rowNum){
            echo "
            <script>alert('아이디와 비밀번호를 확인하세요.');history.back();</script>";
        }

        //관리자 주소로 수정
        echo "<script>alert('관리자님 안녕하세요.');</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/mangerMain.php';</script>";
    }

    else{
        //데이터베이스 검색문        
        $query = "SELECT * FROM 회원 WHERE 아이디='$login_id' and 비밀번호='$login_pw'";

        $res=mysqli_query($mysqli, $query);
        $rowNum=mysqli_num_rows($res);

        // $rowNum이 0이면 아이디와 패스워드가 맞지 않는 것
        if(!$rowNum){
            echo "
            <script>alert('아이디와 비밀번호를 확인하세요.');history.back();</script>";
        }
    
        
        $query2 = "UPDATE `회원` SET `접속일` = '$time_now' WHERE  `아이디`='$login_id';";

        mysqli_query($mysqli, $query2);

        // exit가 안되었다면 로그인이 되었다는 것임!!
        // 다른 페이지에서 로그인 되었다고 인지하기 위해, 회원정보를 세션에 저장
        // 해당하는 id의 회원정보 얻어오기

        $row = mysqli_fetch_array($res);
    

        session_start();
        $_SESSION['userid'] = $row['아이디'];
        $_SESSION['userpw'] = $row['비밀번호'];

        echo "
            <script>
                location.href='http://bookdatabase.dothome.co.kr/main.php';
            </script>";
        
        
        }
?>