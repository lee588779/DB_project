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
        $arrayOrderRefundDate = array();//환불일자
        $arrayOrderRefundPrice = array();//환불총액

        while($row1 = mysqli_fetch_array($res1)){
            $arrayOrderingNumber[] = $row1['주문번호'];
            $arrayOrderingDetail[] = $row1['상세정보'];
            $arrayOrderingPrice[] = $row1['주문총액'];
            $arrayOrderingDay[] = $row1['주문일자'];
            $arrayOrdering[] = $row1['주문상태'];
            $arrayOrderingNot[] = $row1['반품사유'];
            $arrayOrderRefundDate[] = $row1['환불일자'];
            $arrayOrderRefundPrice[] = $row1['환불총액'];
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

        var arrayOrderingNumber =  <?php echo json_encode($arrayOrderingNumber)?>; //주문번호
        var arrayOrderingDetail =  <?php echo json_encode($arrayOrderingDetail)?>;     //상세정보
        var arrayOrderingPrice =  <?php echo json_encode($arrayOrderingPrice)?>;         //주문총액
        var arrayOrderingDay = <?php echo json_encode($arrayOrderingDay)?>;        //주문일자
        var arrayOrdering =  <?php echo json_encode($arrayOrdering)?>;              //주문상태
        var arrayOrderingNot = <?php echo json_encode($arrayOrderingNot)?>;    //반품사유
        var arrayOrderRefundDate = <?php echo json_encode($arrayOrderRefundDate)?>  //환불일자
        var arrayOrderRefundPrice = <?php echo json_encode($arrayOrderRefundPrice)?>  //환불총액
        
                
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
                document.write('환불총액');
                document.write('</td>');

                document.write('<td>');
                document.write('주문상태');
                document.write('</td>')

                document.write('<td>');
                document.write('환불일자');
                document.write('</td>');


                
                for(i=0; i < <?php echo count($arrayOrderingNumber)?>; i++){

                    if(arrayOrdering[i] != '준비중' && arrayOrdering[i] != '배송중' && arrayOrdering[i] != '배송완료'){

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
                        
                        //환불총액
                        document.write('<td>');
                        document.write('<input type="hidden" name = "OrderingPrice" value=' + arrayOrderRefundPrice[i] + '>');
                        document.write(arrayOrderRefundPrice[i]);
                        document.write('</td>');
                        
                        //주문상태
                        document.write('<td>');
                        document.write('<input type="hidden" name = "Ordering" value=' + arrayOrdering[i] + '>');
                        document.write(arrayOrdering[i]);
                        document.write('</td>');

                        //환불일자
                        document.write('<td>');
                        document.write('<input type="hidden" name = "OrderingDay" value=' + arrayOrderRefundDate[i] + '>');
                        document.write(arrayOrderRefundDate[i]);
                        document.write('</td>');


                    }
                }
                    



                document.write('</table>');

            
            
</script>
</html>
