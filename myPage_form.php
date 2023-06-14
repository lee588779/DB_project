<?php 
    
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];


    include "./dbconn.php";

    //회원
    $arrayUserName = array();
    $arrayUserEmail = array();
    $arrayUserNumber = array();
    $arrayUserStudent = array();

    //신용카드정보
    $arrayCardNumber = array();
    $arrayCardKind = array();
    
    //배송지
    $arrayZipCode = array();
    $arrayDefaultAddress = array();
    $arrayDetailAddress = array();

    //패스코드
    $arrayPass = array();
    $arrayPassName = array();

    //회원
    $query1 = "SELECT * FROM `회원` WHERE `아이디` = '$userid';";
    $res1 = mysqli_query($mysqli, $query1);

    while($row1 = mysqli_fetch_array($res1)){
        $arrayUserName[] = $row1['성명'];
        $arrayUserEmail[] = $row1['이메일'];
        $arrayUserNumber[] = $row1['휴대폰번호'];
        $arrayUserStudent[] = $row1['대학생여부'];
        $arrayPass[] = $row1['패스코드'];
    }

    //신용카드정보
    $query2 = "SELECT * FROM `신용카드정보` WHERE `아이디` = '$userid';";
    $res2 = mysqli_query($mysqli, $query2);

    while($row2 = mysqli_fetch_array($res2)){
        $arrayCardNumber[] = $row2['카드번호'];
        $arrayCardKind[] = $row2['카드종류'];
    }

    //배송지
    $query3 = "SELECT * FROM `배송지` WHERE `아이디` = '$userid';";
    $res3 = mysqli_query($mysqli, $query3);

    while($row3 = mysqli_fetch_array($res3)){
        $arrayZipCode[] = $row3['우편번호'];
        $arrayDefaultAddress[] = $row3['기본주소'];
        $arrayDetailAddress[] = $row3['상세주소'];
    }

    //패스명
    $query4 = "SELECT * FROM `패스` WHERE `패스코드` = '$arrayPass[0]';";
    $res4 = mysqli_query($mysqli, $query4);

    while($row4 = mysqli_fetch_array($res4)){
        $arrayPassName[] = $row4['패스명'];
    }

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <h2 align="center"><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
</head>

    <table border="1" width="500" height="300" align="center">
        <tr>
            <td width="20%">
                아이디
            </td>
            <td>
                <?php echo $userid;?>
            </td>
        </tr>
        <tr>
            <td width="20%">
                성명
            </td>
            <td>
                <?php echo $arrayUserName[0];?>
            </td>
        </tr>
        <tr>
            <td width="20%">
                이메일
            </td>
            <td>
                <?php echo $arrayUserEmail[0];?>
            </td>
        </tr>
        <tr>
            <td width="20%">
                휴대폰번호
            </td>
            <td>
                <?php echo $arrayUserNumber[0];?>
            </td>
        </tr>
        <tr>
            <td width="20%">
                대학생여부
            </td>
            <td>
                <?php echo $arrayUserStudent[0];?>
            </td>
        </tr>
        <tr>
            <td width="20%">
                배송지
            </td>
            <td>
                <?php echo $arrayZipCode[0];?>
                <?php echo '      ';?>
                <?php echo $arrayDefaultAddress[0];?>
                <?php echo '      ';?>
                <?php echo $arrayDetailAddress[0];?>
            </td>
        </tr>
        <tr>
            <td width="20%">
                신용카드정보
            </td>
            <td>
                <?php echo $arrayCardKind[0];?>
                <?php echo '      ';?>
                <?php echo $arrayCardNumber[0];?>
            </td>
        </tr>
        <tr>
            <td width="20%">
            <a href="http://bookdatabase.dothome.co.kr/buyPass_form.php">패스</a>
            </td>
            <td>
                <?php echo $arrayPassName[0];?>
            </td>
        </tr>
        <tr>    
            <td colspan="2" align="center">
                <a href="http://bookdatabase.dothome.co.kr/deliver_form.php">구매내역</a>
            </td>
        </tr>
        <tr> 
            <td colspan="2" align="center">
                <a href="http://bookdatabase.dothome.co.kr/refund_form.php">환불내역</a>
            </td>
        </tr>
    </table>
</html>