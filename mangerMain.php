<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <h2 align="center"><a href="http://bookdatabase.dothome.co.kr/mangerMain.php">인터넷도서사이트</a></h2>
    <!-- 공통 스타일시트 연결 -->
    <link rel="stylesheet" href="./common.css">
</head>
        <div id="top">
            <ul id="top_menu">
                    <li><a href="http://bookdatabase.dothome.co.kr/main.php">로그아웃</a></li>
 
            </ul>
        </div>
        <div align="center">
            <form action="http://bookdatabase.dothome.co.kr/mangerBookSelect.php">
                <input type="text" style="text-align:center; width:500px; height:25px;" name="bookName" placeholder="도서입력"/>
                <button style="height:30px;">검색</button>
            </form>
        </div>
</html>
<?php
    $prevPage = $_SERVER['HTTP_REFERER'];
    
    header('loaction:'.$prevPage);
    header("Content-Type: text/html;charset=UTF-8");
    
    include "./dbconn.php";


    $time_now = date("Y-m-d");
?>

<script>
    //테이블 생성
    document.write('<table border="1" align="center" style="text-align:center;" width="500" height="300">');
    document.write('<caption style="padding-top:30px; text-align:left">e북 로테이션 현황</caption>');
                
    //첫줄 설명
    document.write('<tr>');

    document.write('<td>');
    document.write('열람한 회원 비율 : ');
    document.write('</td>');

    document.write('<td>');
    document.write('값삽입');
    document.write('</td>');

    document.write('</tr>');

    document.write('<tr>');

    document.write('<td>');
    document.write('평균독서 진행 비율 : ');
    document.write('</td>');

    document.write('<td>');
    document.write('값삽입');
    document.write('</td>')

    document.write('</tr>');
    document.write('</table>');


    //테이블 생성
    document.write('<table border="1" align="center" style="text-align:center;" width="500" height="300">');
    document.write('<caption style="padding-top:30px; text-align:left">도서판매 현황</caption>');
                
    //첫줄 설명
    document.write('<tr>');

    document.write('<td>');
    document.write('전체 : ');
    document.write('</td>');

    document.write('<td>');
    document.write('값삽입');
    document.write('</td>');

    document.write('</tr>');

    document.write('<tr>');

    document.write('<td>');
    document.write('e북 : ');
    document.write('</td>');

    document.write('<td>');
    document.write('값삽입');
    document.write('</td>')

    document.write('</tr>');

    document.write('<tr>');

    document.write('<td>');
    document.write('도서 : ');
    document.write('</td>');

    document.write('<td>');
    document.write('값삽입');
    document.write('</td>')

    document.write('</tr>');
    document.write('</table>');
</script>