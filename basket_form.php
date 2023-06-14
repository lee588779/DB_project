<?php
	session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

	$prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);

    include "./dbconn.php";
    

    if(!$userid){
		echo "<script>alert('로그인해주세요.')</script>";
        echo "<script>history.back();</script>";
    }else{
        
        //장바구니 아이디 검색문
        $query1 = "SELECT * FROM 장바구니 WHERE 아이디='$userid';";
                
        $res1 = mysqli_query($mysqli, $query1);

        //적립금 검색문
        $query4 = "SELECT * FROM 회원 WHERE 아이디='$userid';";
                
        $res4 = mysqli_query($mysqli, $query4);


        //장바구니번호, 도서번호, 수량
        $arrayBasketNumber = array();
        $arrayBookNumber = array();
        $arrayBookCount = array();

        //도서명,저자,출판사,판매가
        $arrayBookName = array();
        $arrayBookAuthor = array();
        $arrayBookPublish = array();
        $arrayBookPrice = array();

        //적립금 배열
        $arrayUserPoint = array();

        //총액
        $totalPrice = 0;
                        
        while($row1 = mysqli_fetch_array($res1)){
            $arrayBasketNumber[] = $row1['장바구니번호'];
        }

        for($i=0; $i < count($arrayBasketNumber); $i++){
            $j=$arrayBasketNumber[$i];
            $query2 = "SELECT * FROM 장바구니항목 WHERE 장바구니번호='$j'";

            $res2 = mysqli_query($mysqli, $query2);

            while($row2 = mysqli_fetch_array($res2)){
                $arrayBookNumber[] = $row2['도서번호'];
                $arrayBookCount[] = $row2['수량'];
            }
        }


        for($k=0; $k < count($arrayBookNumber); $k++){
            $l=$arrayBookNumber[$k];
            $query3 = "SELECT * FROM 도서 WHERE 도서번호='$l'";

            $res3 = mysqli_query($mysqli, $query3);

            while($row3 = mysqli_fetch_array($res3)){
                $arrayBookName[] = $row3['도서명'];
                $arrayBookAuthor[] = $row3['저자'];
                $arrayBookPublish[] = $row3['출판사'];
                $arrayBookPrice[] = $row3['판매가'];
            }
        }
        for($t=0; $t<count($arrayBasketNumber);$t++){
            $price = 0;
            $price = $arrayBookPrice[$t]*$arrayBookCount[$t];
            $totalPrice = $price + $totalPrice; 
        }

        //회원의 적립금
        while($row4 = mysqli_fetch_array($res4)){
            $arrayUserPoint[] = $row4['적립금'];
        }
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

<script>

        var arrayBasketNumber =  <?php echo json_encode($arrayBasketNumber)?>; //장바구니번호
        var arrayBookNumber =  <?php echo json_encode($arrayBookNumber)?>;     //도서번호
        var arrayBookName =  <?php echo json_encode($arrayBookName)?>;         //도서명
        var arrayBookCount = <?php echo json_encode($arrayBookCount)?>;        //수량
        var arrayBookAuthor =  <?php echo json_encode($arrayBookAuthor)?>;     //저자
        var arrayBookPublish = <?php echo json_encode($arrayBookPublish)?>;    //출판사
        var arrayBookPrice = <?php echo json_encode($arrayBookPrice)?>;        //판매가
        var totalPrice = <?php echo json_encode($totalPrice)?>;                //총액
        var arrayUserPoint = <?php echo json_encode($arrayUserPoint)?>;  //회원의 적립금

        
                
                //테이블 생성
                document.write('<table align="center" style="text-align:center;">');
                
                //첫줄 설명
                document.write('<tr>');

                document.write('<td>');
                document.write('도서번호');
                document.write('</td>');

                document.write('<td>');
                document.write('도서명');
                document.write('</td>');

                document.write('<td>');
                document.write('판매가');
                document.write('</td>');

                document.write('<td>');
                document.write('수량');
                document.write('</td>');

                document.write('<td>');
                document.write('');//버튼
                document.write('</td>')

                document.write('<td>');
                document.write('');//삭제
                document.write('</td>');
                
                document.write('</tr>');

                for(i=0; i < <?php echo count($arrayBasketNumber)?>; i++){
                    document.write('<form>');
                    document.write('<tr>');

                    //도서번호
                    document.write('<td>');
                    document.write('<input type="hidden" name = "bookNumber" value=' + arrayBookNumber[i] + '>');
                    document.write('<input type="hidden" name = "basketNumber" value=' + arrayBasketNumber[i] + '>');
                    document.write(arrayBookNumber[i]);
                    document.write('</td>');
                    
                    //도서명
                    document.write('<td>');
                    document.write(arrayBookName[i]);
                    document.write('</td>');
                    
                    //판매가
                    document.write('<td>');
                    document.write(arrayBookPrice[i]);
                    document.write('</td>');
                    
                    //수량
                    document.write('<td>');
                    document.write('<input type="hidden" name = "bookCount" value=' + arrayBookCount[i] + '>');
                    document.write(arrayBookCount[i]);
                    document.write('</td>');

                    //+,-버튼
                    document.write('<td>');
                    document.write('<input type="submit" value="+" formaction="http://bookdatabase.dothome.co.kr/bookCountPlus.php">');
                    document.write('<input type="submit" value="-" formaction="http://bookdatabase.dothome.co.kr/bookCountMinus.php">');
                    document.write('</td>');
                    

                    //삭제버튼
                    document.write('<td>');
                    document.write('<input type="submit" value="삭제" formaction="http://bookdatabase.dothome.co.kr/basket_form_delete.php">');
                    document.write('</td>');

                    document.write('</tr>');
                    document.write('</form>');
                }

                //선 긋기
                document.write('<tr>');
                document.write('<td colspan="6" class="line">');
                document.write('</td>');
                document.write('</tr>');

                //회원의 적립금
                document.write('<form>');
                document.write('<tr>');
                document.write('<td colspan="3">');
                document.write('<div style="text-align:center;">');
                document.write('나의 적립금 : ');
                document.write(arrayUserPoint[0]);
                document.write('</div>');
                document.write('</td>');

                //적립금사용
                document.write('<td colspan="3">');
                document.write('<div style="text-align:center;">');
                document.write('적립금사용 : ');
                document.write('<input type="text" placeholder="적립금입력" name="bookPoint">');
                document.write('</div>');
                document.write('</td>');
                document.write('</tr>');

                //총액 구매하기
                document.write('<tr>');
                document.write('<td colspan="6">');
                document.write('<div style="text-align:right;">');
                document.write('총액 : ');
                document.write(totalPrice);
                document.write('<input type="submit" value="구매하기" formaction="http://bookdatabase.dothome.co.kr/buy.php">');
                document.write('</div>');
                document.write('</td>');
                document.write('</form>');
                document.write('</tr>');



                document.write('</table>');

            
            
</script>
</html>
