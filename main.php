<?php session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <h2 align="center"><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
    <!-- 공통 스타일시트 연결 -->
    <link rel="stylesheet" href="./common.css">
</head>
<!-- 헤더 영역의 로고와 회원가입/로그인 표시 영역 -->
<div id="top">
            <!-- 1. 로고영역 -->
            <!-- include되면 삽입된 문서의 위치를 기준으로 -->

            <!-- 2. 회원가입/로그인 버튼 표시 영역 -->
            <ul id="top_menu">
                <!-- 로그인 안되었을 때 -->
                <?php if(!$userid){  ?>
                    <li><a href="http://bookdatabase.dothome.co.kr/login_form.php">로그인</a></li>
                    <li> | </li>
                    <li><a href="http://bookdatabase.dothome.co.kr/signup_form.php">회원가입</a></li>
                <?php }else{ ?>
                    <li><a href="http://bookdatabase.dothome.co.kr/logout.php">로그아웃</a></li>
                    <li> | </li>
                    <li><a href="http://bookdatabase.dothome.co.kr/basket_form.php">장바구니</a></li>
                    <li> | </li>
                    <li><a href="http://bookdatabase.dothome.co.kr/myPage_form.php">마이페이지</a></li>
                    <li> | </li>
                    <li><a href="http://bookdatabase.dothome.co.kr/pass_form.php">패스</a></li>
                    <li> | </li>
                    <li><a href="http://bookdatabase.dothome.co.kr/ebookLocker_form.php">보관함</a></li>
                <?php }?>
 
            </ul>
        </div>

        <div align="center">
            <form action="http://bookdatabase.dothome.co.kr/bookSelect.php">
                <input type="text" style="text-align:center; width:500px; height:25px;" name=bookName placeholder="도서입력"/>
                <button style="height:30px;">검색</button>
            </form>
        </div>
        <div align="center" style="padding-top:30px;">
            <form>
                <input type="submit" name = "ebookRotation" value="e북로테이션 받기" formaction="http://bookdatabase.dothome.co.kr/ebookRotation.php"/>
            </form>
        </div>

<?php 
 
    session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

    $prevPage = $_SERVER['HTTP_REFERER'];

    header('loaction:'.$prevPage);

    include "./dbconn.php";

    $arrayEbookNumber = array();

    $arrayBookName = array();
    $arrayBookAuthor = array();
    $arrayBookPublish = array();
    $arrayBookGenre = array();
    $arrayPassStart = array();
    $arrayPassDead = array();


    $query1 = "SELECT * from `e북로테이션` WHERE `아이디`= '$userid' ORDER BY `e북로테이션마감일` DESC;";
    $res1 = mysqli_query($mysqli, $query1);

    while($row1 = mysqli_fetch_array($res1)){
        $arrayEbookNumber[] = $row1['도서번호'];
        $arrayPassStart[] = $row1['e북로테이션시작일'];
        $arrayPassDead[] = $row1['e북로테이션마감일'];
    }
    for($i=0;$i<count($arrayEbookNumber);$i++){
        $query2 = "SELECT * from `도서` WHERE `도서번호`= '$arrayEbookNumber[$i]';";
        $res2 = mysqli_query($mysqli, $query2);

        while($row2 = mysqli_fetch_array($res2)){
            $arrayBookName[] = $row2['도서명'];
            $arrayBookAuthor[] = $row2['저자'];
            $arrayBookPublish[] = $row2['출판사'];
            $arrayBookGenre[] = $row2['장르코드'];

        }
    }
?>
    
        <script>

                var arrayBookName =  <?php echo json_encode($arrayBookName)?>;
                var arrayEbookNumber = <?php echo json_encode($arrayEbookNumber)?>;
                var arrayBookAuthor =  <?php echo json_encode($arrayBookAuthor)?>;
                var arrayBookPublish = <?php echo json_encode($arrayBookPublish)?>;
                var arrayBookGenre = <?php echo json_encode($arrayBookGenre)?>;
                var arrayPassStart = <?php echo json_encode($arrayPassStart)?>;
                var arrayPassDead = <?php echo json_encode($arrayPassDead)?>;
                
            for(i=0; i < <?php echo count($arrayEbookNumber)?>; i++){
                //테이블 생성
                document.write('<table border="1" width="500" height="300" align="center">');
                
                //e북 도서번호(hidden) + 소장 or 대여
                document.write('<form>');
                document.write('<tr>');
                document.write('<td colspan="6" width="85%" height="10%" align="center">');
                document.write('<input type="hidden" name="bookNumber"  value=' + arrayEbookNumber[i] + '>');
                document.write('기간 :');
                document.write(' ' + arrayPassStart[i]);
                document.write('~');
                document.write(' ' + arrayPassDead[i]);
                document.write('</td>');
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
                document.write(arrayBookName[i]);
                document.write('</div>');

                document.write('</br>');

                document.write('<div>');
                document.write("저자 : ");
                document.write(arrayBookAuthor[i]);
                document.write('</div>');

                document.write('</br>');

                document.write('<div>');
                document.write("출판사 : ");
                document.write(arrayBookPublish[i]);
                document.write('</div>');

                document.write('</br>');

                document.write('<div>');
                document.write("장르 : ");
                document.write(arrayBookGenre[i]);
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