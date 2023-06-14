<?php
    $prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);

    include "./dbconn.php";

    $bookNumber=$_GET['bookNumber']; //도서번호
    $bookName=$_GET['bookName']; //도서명
    $bookCount=$_GET['bookCount']; //재고량
    $bookPrice=$_GET['bookPrice']; //판매가

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <h2 align="center"><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
</head>
<style type="text/css">
    .line{border-bottom: 1px solid gray;}
</style>
<body>
    <div align="center">
        <form action="http://bookdatabase.dothome.co.kr/bookSelect.php">
            <input type="text" name=bookName value = <?php echo $bookName ?>>
            <button>검색</button>
        </form>
    </div>

    <?php
            if(!$bookName){
                echo "<script>alert('단어를 입력해주세요.')</script>";
                echo "<script>history.back();</script>";
            }
            else{
            
                //DB검색문
                $query = "SELECT * FROM `도서` WHERE 도서명 LIKE '%$bookName%';";
                
                $res = mysqli_query($mysqli, $query);

                $arrayBookNumber = array();
                $arrayBookName = array();
                $arrayBookAuthor = array();
                $arrayBookPublish = array();
                $arrayBookPrice = array();

                //e북 장르코드
                $arrayEbookGenre = array();
                //e북 장르명
                $arrayEbookGenreName = array();
                        
                while($row = mysqli_fetch_array($res)){
                    $arrayBookNumber[] = $row['도서번호'];
                    $arrayBookName[] = $row['도서명'];
                    $arrayBookAuthor[] = $row['저자'];
                    $arrayBookPublish[] = $row['출판사'];
                    $arrayBookPrice[] = $row['판매가'];
                    $arrayEbookGenre[] = $row['장르코드'];
                }

                for($i=0;$i<count($arrayBookNumber);$i++){
                    $query3 = "SELECT * FROM `장르` WHERE `장르코드` = '$arrayEbookGenre[$i]';";
                    $res3 = mysqli_query($mysqli, $query3);
                    while($row3 = mysqli_fetch_array($res3)){
                        $arrayEbookGenreName[] = $row3['장르명'];
                    }
                }
            }
    ?>
    


    <script>

        var arrayBookName =  <?php echo json_encode($arrayBookName)?>;
        var arrayBookNumber = <?php echo json_encode($arrayBookNumber)?>;
        var arrayBookAuthor =  <?php echo json_encode($arrayBookAuthor)?>;
        var arrayBookPublish = <?php echo json_encode($arrayBookPublish)?>;
        var arrayBookPrice = <?php echo json_encode($arrayBookPrice)?>;
        var arrayEbookGenreName = <?php echo json_encode($arrayEbookGenreName)?>;       //e북 장르명
        

            for(i=0; i< <?php echo count($arrayBookName); ?>; i++){
                
                //테이블 생성
                document.write('<table border="1" width="500" height="300" align="center">');
                
                //도서번호
                document.write('<form>');
                document.write('<tr>');
                document.write('<td colspan="6" width="85%" height="10%">');
                document.write('<input type="hidden" name="bookNumber"  value=' + arrayBookNumber[i] + '>');
                document.write('<input type="submit" value="상세정보" style="float:right" formaction="http://bookdatabase.dothome.co.kr/detail.php">');
                document.write('</td>');;
                document.write('</td>');
                document.write('</tr>');
                document.write('</form>');


                //이미지
                document.write('<form>');
                document.write('<tr>');
                document.write('<td rowspan="2" width="20%" height="85%" align="center">');
                document.write('<img src="null">');
                document.write('</td>');

                //도서명 저자 출판사
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
                document.write(arrayEbookGenreName[i]);
                document.write('</div>');

                document.write('</td>');
                

                //장바구니 버튼
                document.write('<form>');
                document.write('<td width="15%" height="30%">');
                document.write('<input type="hidden" name="bookNumber"  value=' + arrayBookNumber[i] + '>');
                document.write('<input type="submit" value="장바구니" formaction="http://bookdatabase.dothome.co.kr/basketButton.php">');
                document.write('</tr>');
                document.write('</form>');

                //판매가
                document.write('<tr>');
                document.write('<td colspan="6" height="4%" align="right">');
                document.write('<div>');
                document.write("판매가 : ");
                document.write(arrayBookPrice[i]);
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
</body>