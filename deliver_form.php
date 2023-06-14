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
        

        //주문상태 검색
        $query1 = "SELECT * FROM 주문 WHERE 아이디='$userid';";

        $res1 = mysqli_query($mysqli, $query1);

        $arrayOrderingNumber = array();  //주문번호
        $arrayOrderingDetail = array();  //상세정보
        $arrayOrderingPrice = array();  //주문총액, 환불금액
        $arrayOrderingDay = array();  //주문일자
        $arrayOrdering = array();  //주문상태
        $arrayOrderingNot = array();  //반품사유
        $arrayOrderCount = array();   //수량

        

        while($row1 = mysqli_fetch_array($res1)){
            $arrayOrderingNumber[] = $row1['주문번호'];
            $arrayOrderingDetail[] = $row1['상세정보'];
            $arrayOrderingPrice[] = $row1['주문총액'];
            $arrayOrderingDay[] = $row1['주문일자'];
            $arrayOrdering[] = $row1['주문상태'];
            $arrayOrderingNot[] = $row1['반품사유'];
            $arrayOrderCount[] = $row1['수량'];
        }
        
        

        //도서명
        $arrayBookName = array();

        $query2 = "SELECT `도서명` FROM `도서`;";

        $res2 = mysqli_query($mysqli, $query2);

        while($row2 = mysqli_fetch_array($res2)){
            $arrayBookName[] = $row2['도서명'];
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

        var arrayOrderingNumber =  <?php echo json_encode($arrayOrderingNumber)?>;       //주문번호
        var arrayOrderingDetail =  <?php echo json_encode($arrayOrderingDetail)?>;       //상세정보
        var arrayOrderingPrice =  <?php echo json_encode($arrayOrderingPrice)?>;         //주문총액
        var arrayOrderingDay = <?php echo json_encode($arrayOrderingDay)?>;              //주문일자
        var arrayOrdering =  <?php echo json_encode($arrayOrdering)?>;                   //주문상태
        var arrayOrderingNot = <?php echo json_encode($arrayOrderingNot)?>;              //반품사유
        var arrayBookName = <?php echo json_encode($arrayBookName)?>;                    //도서명
        var arrayOrderCount = <?php echo json_encode($arrayOrderCount)?>;                //수량
        
        var selectOption = "";                                                           //옵션추가
                
                //테이블 생성
                document.write('<table align="center" style="text-align:center;">');
                
                //첫줄 설명
                document.write('<tr>');

                document.write('<td>');
                document.write('주문번호');
                document.write('</td>');

                document.write('<td>');
                document.write('상세정보');
                document.write('</td>');

                document.write('<td>');
                document.write('주문총액');
                document.write('</td>');

                document.write('<td>');
                document.write('주문상태');
                document.write('</td>')

                document.write('<td>');
                document.write('주문일자');
                document.write('</td>');

                document.write('<td>');
                document.write('반품사유');
                document.write('</td>');

                document.write('<td>');
                document.write('선택');
                document.write('</td>');
                

                document.write('<td>');
                document.write('');    //환불버튼
                document.write('</td>');
                
                document.write('</tr>');

                for(i=0; i < <?php echo count($arrayOrderingNumber)?>; i++){

                    if(arrayOrdering[i] == '준비중' || arrayOrdering[i] == '배송중' || arrayOrdering[i] == '배송완료'){
                        document.write('<form>');
                        document.write('<tr>');

                        //주문번호
                        document.write('<td>');
                        document.write('<input type="hidden" name = "OrderingNumber" value=' + arrayOrderingNumber[i] + '>');
                        document.write(arrayOrderingNumber[i]);
                        document.write('</td>');
                        
                        //상세정보
                        document.write('<td>');
                        document.write('<input type="hidden" name = "OrderingDetail" value=' + arrayOrderingDetail[i] + '>');
                        document.write(arrayOrderingDetail[i]);
                        document.write('</td>');
                        
                        //주문총액
                        document.write('<td>');
                        document.write('<input type="hidden" name = "OrderingPrice" value=' + arrayOrderingPrice[i] + '>');
                        document.write(arrayOrderingPrice[i]);
                        document.write('</td>');
                        
                        //주문상태
                        document.write('<td>');
                        document.write('<input type="hidden" name = "Ordering" value=' + arrayOrdering[i] + '>');
                        document.write(arrayOrdering[i]);
                        document.write('</td>');

                        //주문일자
                        document.write('<td>');
                        document.write('<input type="hidden" name = "OrderingDay" value=' + arrayOrderingDay[i] + '>');
                        document.write(arrayOrderingDay[i]);
                        document.write('</td>');

                        //환불사유
                        document.write('<td>');
                        document.write('<select name="deliverNo" size="1">');
                        document.write('<option value = "도서불량">도서불량</option>');
                        document.write('<option value = "고객변심">고객변심</option>');
                        document.write('</select>');
                        document.write('</td>');

                        

                        //환불버튼
                        document.write('<td>');
                        document.write('<input type="submit" value="환불" formaction="http://bookdatabase.dothome.co.kr/refund.php">');
                        document.write('</td>');

                        document.write('</tr>');
                        document.write('</form>');
                    }
                    
                }

                document.write('</table>');

            
</script>
</html>
