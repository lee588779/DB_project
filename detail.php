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

    $arrayBookNumber = array();   //도서번호
    $arrayBookName = array();     //도서명
    $arrayBookAuthor = array();   //저자
    $arrayBookPublish = array();  //출판사
    $arrayBookPrice = array();    //판매가
    $arrayBookStroy = array();    //줄거리
    $arrayEbookGenre = array();   //장르코드
    //e북
    $arrayBookLookCountNum = array(); //도서조회이력번호
    $arrayEbookNumber = array();   //도서번호
    $arrayBookLookCount = array();   //도서조회횟수

    //도서번호로 도서검색문
    $query = "SELECT * FROM `도서` WHERE 도서번호='$bookNumber';";
        
    $res = mysqli_query($mysqli, $query);

    //도서조회이력번호 생성
    $query2 = "SELECT * FROM `도서조회이력` ORDER BY `도서조회이력번호` DESC";

    $res2 = mysqli_query($mysqli, $query2);

    while($row2 = mysqli_fetch_array($res2)){
        $arrayBookLookCountNum[] = $row2['도서조회이력번호'];
        $arrayBookLookCount[] = $row2['도서조회횟수'];
    }

    $bookCountLookNum = $arrayBookLookCountNum[0] + 1;

                        
    while($row = mysqli_fetch_array($res)){
        $arrayBookNumber[] = $row['도서번호'];
        $arrayBookName[] = $row['도서명'];
        $arrayBookAuthor[] = $row['저자'];
        $arrayBookPublish[] = $row['출판사'];
        $arrayBookPrice[] = $row['판매가'];
        $arrayBookStroy[] = $row['줄거리'];
        $arrayEbookGenre[] = $row['장르코드'];
    }
    //도서조회이력 추가 및 업데이트
    $query3 = "SELECT * FROM `도서조회이력` WHERE `아이디`='$userid' AND `도서번호`='$bookNumber';";

    $res3 = mysqli_query($mysqli, $query3);

    while($row3 = mysqli_fetch_array($res3)){
        $arrayEbookNumber[] = $row3['도서번호'];
    }

    $time_now = date("Y-m-d");

    if($arrayEbookNumber[0]!=$bookNumber){
        $query4 = "INSERT INTO `도서조회이력`(`도서조회이력번호`, `도서최종조회날짜`, `도서조회횟수`, `도서번호`, `아이디`, `장르코드`) VALUES ('$bookCountLookNum', '$time_now', '1','$bookNumber', '$userid', '$arrayEbookGenre[0]')";

        mysqli_query($mysqli, $query4);
    }else{
        $bookLookCount = 0;

        $bookLookCount = $arrayBookLookCount[0]+1;

        $query5 = "UPDATE `도서조회이력` SET `도서조회횟수` = '$bookLookCount', `도서최종조회날짜` = '$time_now' WHERE `아이디`='$userid' AND `도서번호`='$bookNumber';";
        mysqli_query($mysqli, $query5);
    }
    
?>

<!DOCTYPE html>
<html>
<head>
<h2 align="center"><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
    .line{border-bottom: 1px solid gray;}
</style>
</head>
<body>
    <script>

        var arrayBookName =  <?php echo json_encode($arrayBookName)?>;
        var arrayBookNumber = <?php echo json_encode($arrayBookNumber)?>;
        var arrayBookAuthor =  <?php echo json_encode($arrayBookAuthor)?>;
        var arrayBookPublish = <?php echo json_encode($arrayBookPublish)?>;
        var arrayBookPrice = <?php echo json_encode($arrayBookPrice)?>;
        var arrayBookStroy = <?php echo json_encode($arrayBookStroy)?>;
                
                //테이블 생성
                document.write('<form>');
                document.write('<table border="1" width="500" height="300" align="center">');
                
                //도서번호
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<input type="hidden" name="bookNumber"  value=' + arrayBookNumber[0] + '>');
                document.write('<div>');
                document.write("도서번호 : ");
                document.write(arrayBookNumber[0]);
                document.write('</div>');
                document.write('</td>');
                document.write('<tr>');

                //도서명
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<div>');
                document.write("도서명 : ");
                document.write(arrayBookName[0]);
                document.write('</div>');
                document.write('</td>');
                document.write('<tr>');
                

                //저자
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<div>');
                document.write("저자 : ");
                document.write(arrayBookAuthor[0]);
                document.write('</div>');
                document.write('</td>');
                document.write('<tr>');

                //출판사
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<div>');
                document.write("출판사 : ");
                document.write(arrayBookPublish[0]);
                document.write('</div>');
                document.write('</td>');
                document.write('<tr>');

                //판매가
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<div>');
                document.write("판매가 : ");
                document.write(arrayBookPrice[0]);
                document.write('</div>');
                document.write('</td>');
                document.write('</tr>');
                
                //줄거리
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<div>');
                document.write("줄거리");
                document.write('</div>');
                document.write('</td>');
                document.write('</tr>');

                //줄거리 내용
                document.write('<tr>');
                document.write('<td width="100%">');
                document.write('<div>');
                document.write(arrayBookStroy[0]);
                document.write('</div>');
                document.write('</td>');
                document.write('</tr>');

                //열람하기버튼
                document.write('<tr>');
                document.write('<td width="100%" align="right">');
                document.write('<div>');
                document.write('<input type="submit" value="열람하기" formaction="http://bookdatabase.dothome.co.kr/ebookRead.php">');
                document.write('</div>');
                document.write('</td>');
                document.write('</tr>');

                

                document.write('</table>');
                document.write('</form>');
                
                
    </script>
</body>