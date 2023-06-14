<?php 
    
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];


    include "./dbconn.php";

    //e북도서번호
    $arrayEbookNumber = array();
    //e북 소장/패스/로테이션
    $arrayEbookKind = array();
    //e북 마감기간
    $arrayEbookDeadline = array();
    //e북 독서현재진행비율
    $arrayEbookNowReading = array();
    //e북도서명
    $arrayEbookName = array();
    //e북 저자
    $arrayEbookAuthor = array();
    //e북 출판사
    $arrayEbookPublish = array();
    //e북 장르코드
    $arrayEbookGenre = array();
    //e북 장르명
    $arrayEbookGenreName = array();
    

    //e북도서번호 검색
    $query1 = "SELECT * FROM `e북보관함` WHERE `아이디` = '$userid';";
    $res1 = mysqli_query($mysqli, $query1);

    while($row1 = mysqli_fetch_array($res1)){
        $arrayEbookNumber[] = $row1['도서번호'];
        $arrayEbookKind[] = $row1['소장/패스/로테이션'];
        $arrayEbookDeadline[] = $row1['마감기간'];
        $arrayEbookNowReading[] = $row1['독서현재진행비율'];
    }

    //e북도서번호로 도서정보와 장르 추출
    for($i=0;$i<count($arrayEbookNumber);$i++){
        $query2 = "SELECT * FROM `도서` WHERE `도서번호` = '$arrayEbookNumber[$i]';";
        $res2 = mysqli_query($mysqli, $query2);

        while($row2 = mysqli_fetch_array($res2)){
            $arrayEbookName[] = $row2['도서명'];
            $arrayEbookAuthor[] = $row2['저자'];
            $arrayEbookPublish[] = $row2['출판사'];
            $arrayEbookGenre[] = $row2['장르코드'];
        }

        $query3 = "SELECT * FROM `장르` WHERE `장르코드` = '$arrayEbookGenre[$i]';";
        $res3 = mysqli_query($mysqli, $query3);
        while($row3 = mysqli_fetch_array($res3)){
            $arrayEbookGenreName[] = $row3['장르명'];
        }
    }

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <h2 align="center"><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
</head>

<script>

var arrayEbookNumber =  <?php echo json_encode($arrayEbookNumber)?>;            //e북 도서번호
var arrayEbookName = <?php echo json_encode($arrayEbookName)?>;                 //e북 도서명
var arrayEbookAuthor =  <?php echo json_encode($arrayEbookAuthor)?>;            //e북 저자
var arrayEbookPublish = <?php echo json_encode($arrayEbookPublish)?>;           //e북 출판사
var arrayEbookGenre = <?php echo json_encode($arrayEbookGenre)?>;               //e북 장르 코드
var arrayEbookGenreName = <?php echo json_encode($arrayEbookGenreName)?>;       //e북 장르명
var arrayEbookKind = <?php echo json_encode($arrayEbookKind)?>;                 //e북 소장/패스/로테이션
var arrayEbookDeadline = <?php echo json_encode($arrayEbookDeadline)?>;         //e북 마감기간
var arrayEbookNowReading = <?php echo json_encode($arrayEbookNowReading)?>;     //e북 독서현재진행비율


    for(i=0; i< <?php echo count($arrayEbookNumber); ?>; i++){
        
        //테이블 생성
        document.write('<table border="1" width="500" height="300" align="center">');
        
        //e북 도서번호(hidden) + 소장 or 대여
        document.write('<form>');
        document.write('<tr>');
        document.write('<td colspan="6" width="85%" height="10%">');
        document.write('<input type="hidden" name="bookNumber"  value=' + arrayEbookNumber[i] + '>');

        document.write('<div align="right">');
        document.write(arrayEbookKind[i]);
        document.write('(');
        document.write(arrayEbookNowReading[i]);
        document.write('%)');
        document.write('</div>');

        document.write('</td>');;
        document.write('</td>');
        document.write('</tr>');


        //이미지
        document.write('<tr>');
        document.write('<td rowspan="2" width="20%" height="85%" align="center">');
        document.write('<img src="null">');
        document.write('</td>');

        //도서명 저자 출판사 장르
        document.write('<td colspan="4" width="65%" height="30%">');

        document.write('<div>');
        document.write("도서명 : ");
        document.write(arrayEbookName[i]);
        document.write('</div>');

        document.write('</br>');

        document.write('<div>');
        document.write("저자 : ");
        document.write(arrayEbookAuthor[i]);
        document.write('</div>');

        document.write('</br>');

        document.write('<div>');
        document.write("출판사 : ");
        document.write(arrayEbookPublish[i]);
        document.write('</div>');

        document.write('</br>');

        document.write('<div>');
        document.write("장르 : ");
        document.write(arrayEbookGenreName[i]);
        document.write('</div>');
        
        document.write('</td>');
        

        //열람하기
        document.write('<td width="15%" height="30%">');
        document.write('<input type="submit" value="열람하기" formaction="http://bookdatabase.dothome.co.kr/ebookRead.php">');
        document.write('</td>');
        document.write('</tr>');
        document.write('</form>');

        //마감기간
        document.write('<tr>');
        document.write('<td colspan="6" height="4%" align="right">');
        document.write('<div>');
        document.write("마감기간 : ");
        document.write(arrayEbookDeadline[i]);
        document.write('</div>');
        document.write('</td>');
        document.write('</tr>');
        //선 긋기
        document.write('<tr>');
        document.write('<td colspan="6" height="1%" class="line">');
        document.write('</td>');
        document.write('</tr>');

        document.write('</table>');
        
        
    }
</script>
</html>