<?php
	session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

	$prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);

    include "./dbconn.php";

    $bookNumber=$_GET['bookNumber']; //도서번호


    $arraybookPage = array();
    $arrayNowPage = array();


    //도서 전체페이지 검색문
    $query1 = "SELECT * FROM 도서 WHERE 도서번호='$bookNumber';";
                
    $res1 = mysqli_query($mysqli, $query1);

    //전체페이지
    while($row1 = mysqli_fetch_array($res1)){
        $arraybookPage[] = $row1['전체페이지'];
    }
    //도서 현재페이지 검색문
    $query3 = "SELECT * FROM `e북보관함` WHERE `도서번호`='$bookNumber' and `아이디`= '$userid';";
                
    $res3 = mysqli_query($mysqli, $query3);

    //전체페이지
    while($row3 = mysqli_fetch_array($res3)){
        $arrayNowPage[] = $row3['현재페이지'];
    }

    $arrayUserPass = array();
    $arrayBookNumber = array();
    $arrayDeadLine = array();
    $arrayEbookNumber = array();
    $arrayKind = array();

    $arrayEbookRDL = array();
    $arrayOpen = array();
    $arrayPassDeadLine = array();

    //회원이 패스 or 구매를 했는지 + 로테이션(추후 추가)
    $query2 = "SELECT * FROM 회원 WHERE 아이디='$userid';";
                
    $res2 = mysqli_query($mysqli, $query2);

    //회원의 패스코드 찾기
    while($row2 = mysqli_fetch_array($res2)){
        $arrayUserPass[] = $row2['패스코드'];
    }



    //e북보관함 아이디, 도서번호 존재하는지 유무 검색
    $query6 = "SELECT * FROM e북보관함 WHERE 아이디='$userid' AND 도서번호='$bookNumber';";
                
    $res6 = mysqli_query($mysqli, $query6);

    while($row6 = mysqli_fetch_array($res6)){
        $arrayEbookNumber[] = $row6['도서번호'];
        $arrayDeadLine[] = $row6['마감기간'];
        $arrayKind[] = $row6['소장/패스/로테이션'];
    }


    //e북로테이션에서 열람여부 마감일 검색
    $query8 = "SELECT * FROM e북로테이션 WHERE 아이디='$userid' AND 도서번호='$bookNumber';";
                
    $res8 = mysqli_query($mysqli, $query8);

    while($row8 = mysqli_fetch_array($res8)){
        $arrayEbookRDL[] = $row8['e북로테이션마감일'];
        $arrayOpen[] = $row8['열람여부'];
    }

    //패스구매내역 패스마감일
    $query10 = "SELECT * FROM 패스구매내역 WHERE 아이디='$userid' ORDER BY `패스마감일` DESC;";
                
    $res10 = mysqli_query($mysqli, $query10);

    while($row10 = mysqli_fetch_array($res10)){
        $arrayPassDeadLine[] = $row10['패스마감일'];
    }



    $time_now = date("Y-m-d");

    if($time_now > $arrayPassDeadLine[0]){
        echo "<script>alert('마감기간이 지났습니다. 구매를 하거나 패스를 연장해주세요')</script>";
        echo "<script>history.back();</script>";
    }else{
        if(SUBSTR($bookNumber,0,1)=='e'){

            if($arrayUserPass[0]!='P-0' OR $arrayKind[0]=='로테이션' OR $arrayKind[0]=='소장'){
    
                if($arrayEbookNumber[0]!=$bookNumber){
                    $query4 = "INSERT INTO `e북보관함`(`아이디`, `도서번호`, `현재페이지`, `열람마지막날짜`, `소장/패스/로테이션`, `마감기간`, `독서현재진행비율`, `독서최고진행비율`) VALUES ('$userid', '$bookNumber', 1, '$time_now', '패스', '$arrayPassDeadLine[0]', '0', '0');";
                    mysqli_query($mysqli, $query4);
                }
                else{
                    $query7 = "UPDATE `e북보관함` SET `열람마지막날짜` = '$time_now'WHERE  `아이디`='$userid' AND `도서번호` = '$bookNumber';";
                    mysqli_query($mysqli, $query7);

                    if($arrayEbookRDL[0]==$arrayDeadLine[0] AND $arrayKind[0]=='로테이션'){
                        $query9 = "UPDATE `e북로테이션` SET `열람여부` = '1' WHERE  `아이디`='$userid' AND `도서번호` = '$bookNumber';";
                        mysqli_query($mysqli, $query9);
                    }

                }
    
            }else{
                echo "<script>alert('패스를 이용하거나 e북을 구매해주세요')</script>";
                echo "<script>history.back();</script>";
            }
        }else{
            echo "<script>alert('e북전용 버튼입니다.')</script>";
            echo "<script>history.back();</script>";
        }
    }



?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <h2 align="center"><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
</head>

<script>    
            var arraybookPage =  <?php echo json_encode($arraybookPage)?>;
            var arrayNowPage =  <?php if($arrayNowPage[0]=='0' OR $arrayNowPage[0]==NULL){$arrayNowPage[0]='1';}echo json_encode($arrayNowPage)?>;
            var bookNumber = <?php echo json_encode($bookNumber) ?>
            


            //테이블 생성
            document.write('<table border="1" width="500" height="800" align="center" style="text-align:center;">');
                
            //'<'표시
            document.write('<form>');
            document.write('<tr>');
            
            document.write('<td rowspan="9" colsapn="1" width="15%" height="85%">');
            document.write('<input type="hidden" name = "bookNumber" value=' + bookNumber + '>');
            document.write('<input type="submit" value="<" formaction="http://bookdatabase.dothome.co.kr/ebookPageMinus.php">');
            document.write('</td>');

            //책이미지
            document.write('<td rowspan="9" colspan="8" width="70%" height="85%">');
            document.write('<img src="null">');
            document.write('</td>');

            //'>'표시
            document.write('<td rowspan="9" colsapn="1" width="15%" height="85%">');
            document.write('<input type="submit" value=">" formaction="http://bookdatabase.dothome.co.kr/ebookPagePlus.php">');
            document.write('</td>');
                
            document.write('</tr>');
            document.write('</form>');

            document.write('</table>');

            //현재페이지/전체페이지
            document.write('<table align="center">');
            document.write('<tr>');
            document.write('<td align="center">');
            document.write(arrayNowPage[0]);
            document.write('/')
            document.write(arraybookPage[0])
            document.write('</td>');
            document.write('</tr>');

            document.write('</table>');
</script>