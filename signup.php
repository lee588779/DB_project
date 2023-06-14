<?php
    include "./dbconn.php";


    //회원
    $signup_id = $_GET['signup_id'];              //아이디
    $signup_pw = $_GET['signup_pw'];              //비밀번호
    $signup_name = $_GET['signup_name'];          //성명
    $signup_email = $_GET['signup_email'];        //이메일
    $signup_number = $_GET['signup_number'];      //휴대폰번호
    $studentYN = $_GET['studentYN'];              //대학생여부

    //아이디,비밀번호, 성명        
    $query1 = "SELECT * FROM 회원 WHERE 아이디='$signup_id'";

    $res=mysqli_query($mysqli, $query1);
    
    $arraySignup_id = array();
                        
    while($row = mysqli_fetch_array($res)){
        $arraySignup_id[] = $row['아이디'];
    }

    //관리자 아이디,비밀번호
    $query5 = "SELECT `관리자아이디` FROM `관리자`;";

    $res5=mysqli_query($mysqli, $query5);

    $arrayManagerid = array();

    while($row5 = mysqli_fetch_array($res5)){
        $arrayManagerid[] = $row5['관리자아이디'];
    }


    //카드정보, 배송지
    $cardNumber = $_GET['cardNumber'];
    $cardDate = $_GET['cardDate'];
    $cardKind = $_GET['cardKind'];
    
    $zipCode = $_GET['zipCode'];
    $defaultAddress = $_GET['defaultAddress'];
    $detailAddress = $_GET['detailAddress'];
        

    $time_now = date("Y-m-d");


    if(!$signup_id){
        echo "<script>alert('아이디를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/signup_form.php';</script>";
    }
    if(!$signup_pw){
        echo "<script>alert('패스워드를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/signup_form.php';</script>";
    }
    if(!$signup_name){
        echo "<script>alert('성명을 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/signup_form.php';</script>";
    }
    if(!$cardNumber){
        echo "<script>alert('카드번호를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/register_form.php'</script>";
    }
    if(!$cardDate){
        echo "<script>alert('유효기간을 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/register_form.php'</script>";
    }
    if(!$cardKind){
        echo "<script>alert('카드종류를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/register_form.php'</script>";
    }
    if(!$zipCode){
        echo "<script>alert('우편번호를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/register_form.php'</script>";
    }
    if(!$defaultAddress){
        echo "<script>alert('기본주소를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/register_form.php'</script>";
    }
    if(!$detailAddress){
        echo "<script>alert('상세주소를 적어주세요.')</script>";
        echo "<script>location.href='http://bookdatabase.dothome.co.kr/register_form.php'</script>";
    }
    //데이터베이스 삽입문
    if($arraySignup_id[0]!=$signup_id AND $arrayManagerid[0]!=$signup_id){
        
        $query2 = "INSERT INTO 회원(아이디, 비밀번호, 성명, 적립금, 이메일, 접속일, 휴대폰번호, 대학생여부, 변경여부, 해지여부, 패스코드) VALUES ('$signup_id', '$signup_pw', '$signup_name', '0', '$signup_email', '$time_now', '$signup_number', '$studentYN', '0', '0', 'P-0');";

        mysqli_query($mysqli, $query2);

        $query3 = "INSERT INTO 신용카드정보(카드번호, 유효기간, 카드종류, 아이디) VALUES ('$cardNumber', '$cardDate', '$cardKind', '$signup_id')";
        $query4 = "INSERT INTO 배송지(우편번호, 기본주소, 상세주소, 아이디) VALUES ('$zipCode', '$defaultAddress', '$detailAddress', '$signup_id')";

        mysqli_query($mysqli, $query3);
        mysqli_query($mysqli, $query4);

        echo "<script>alert('가입이 완료되었습니다.')</script>";
        echo "
            <script>
                location.href='http://bookdatabase.dothome.co.kr/main.php';
            </script>";
    }
    else{
        echo "<script>alert('중복확인을 해주세요.')</script>";
        echo "<script>history.back();</script>";
    }

    mysqli_close($mysqli);
?>
    