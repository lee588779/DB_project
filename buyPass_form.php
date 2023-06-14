<?php
	session_start();
    $userid="";
    $userpw="";

    if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
    if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

	$prevPage = $_SERVER['HTTP_REFERER'];
   
    header('loaction:'.$prevPage);

    include "./dbconn.php";

        $arrayPassOrderNum = array();  //패스구매코드
        $arrayPassStart = array();     //패스시작일
        $arrayPassDeadLine = array();  //패스마감일
        $arrayPassNum = array();       //패스코드

        $arrayPassName = array();  //패스명
        $arrayPassPrice = array();     //패스가격
    

        //패스구매내역
        $query1 = "SELECT * FROM `패스구매내역` WHERE 아이디='$userid';";

        $res1 = mysqli_query($mysqli, $query1);

        while($row1 = mysqli_fetch_array($res1)){
            $arrayPassOrderNum[] = $row1['패스구매코드'];
            $arrayPassStart[] = $row1['패스시작일'];
            $arrayPassDeadLine[] = $row1['패스마감일'];
            $arrayPassNum[] = $row1['패스코드'];
        }

        //패스명 패스가격
        for($i=0;$i<count($arrayPassOrderNum);$i++){
            $query2 = "SELECT * FROM `패스` WHERE `패스코드` = '$arrayPassNum[$i]';";

            $res2 = mysqli_query($mysqli, $query2);

            while($row2 = mysqli_fetch_array($res2)){
                $arrayPassName[] = $row2['패스명'];
                $arrayPassPrice[] = $row2['패스가격'];
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

        var arrayPassOrderNum =  <?php echo json_encode($arrayPassOrderNum)?>;       //패스구매코드
        var arrayPassStart =  <?php echo json_encode($arrayPassStart)?>;             //패스시작일
        var arrayPassDeadLine =  <?php echo json_encode($arrayPassDeadLine)?>;       //패스마감일
        var arrayPassNum = <?php echo json_encode($arrayPassNum)?>;                  //패스코드
        var arrayPassName =  <?php echo json_encode($arrayPassName)?>;               //패스명
        var arrayPassPrice = <?php echo json_encode($arrayPassPrice)?>;              //패스가격
                
                //테이블 생성
                document.write('<table align="center" style="text-align:center;">');
                
                //첫줄 설명
                document.write('<tr>');

                document.write('<td>');
                document.write('패스구매코드');
                document.write('</td>');

                document.write('<td>');
                document.write('패스시작일');
                document.write('</td>');

                document.write('<td>');
                document.write('패스마감일');
                document.write('</td>');

                document.write('<td>');
                document.write('패스명');
                document.write('</td>')

                document.write('<td>');
                document.write('패스가격');
                document.write('</td>');
                
                document.write('</tr>');

                for(i=0; i < <?php echo count($arrayPassOrderNum)?>; i++){

                        document.write('<tr>');

                        //패스구매코드
                        document.write('<td>');
                        document.write(arrayPassOrderNum[i]);
                        document.write('</td>');
                        
                        //패스시작일
                        document.write('<td>');
                        document.write(arrayPassStart[i]);
                        document.write('</td>');
                        
                        //패스마감일
                        document.write('<td>');
                        document.write(arrayPassDeadLine[i]);
                        document.write('</td>');
                        
                        //패스명
                        document.write('<td>');
                        document.write(arrayPassName[i]);
                        document.write('</td>');

                        //패스가격
                        document.write('<td>');
                        document.write(arrayPassPrice[i]);
                        document.write('</td>');

                        document.write('</tr>');
                    
                }

                document.write('</table>');

            
</script>
</html>
