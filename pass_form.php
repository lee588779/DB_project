<?php
session_start();
$userid="";
$userpw="";

if( isset($_SESSION['userid'])) $userid= $_SESSION['userid'];
if( isset($_SESSION['userpw'])) $username= $_SESSION['userpw'];

$prevPage = $_SERVER['HTTP_REFERER'];

header('loaction:'.$prevPage);

include "./dbconn.php";

$arrayPassName = array();
$arrayPassPrice = array();
$arraypassCode = array();

$query1 = "SELECT * FROM 패스;";

$res1 = mysqli_query($mysqli, $query1);

while($row1 = mysqli_fetch_array($res1)){
    $arraypassCode[] = $row1['패스코드'];
    $arrayPassName[] = $row1['패스명'];
    $arrayPassPrice[] = $row1['패스가격'];
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
    <form>
        <table align="center" style="text-align:center;">
        <tr>
            <td>
                패스명
            </td>
            <td>
                패스가격
            </td>
            <td>
            </td>
        </tr>
        
        <tr>
            <td>
                <input type="hidden" name="passCode" value = <?php echo $arraypassCode[1];?>>
                <?php echo $arrayPassName[1];?>
            </td>
            <td>
                <?php echo $arrayPassPrice[1];?>원
            </td>
            <td>
                <input type="submit" value="선택" formaction="http://bookdatabase.dothome.co.kr/userPass.php">
            </td>
        </tr>
    </form>

    <form>
        <tr>
            <td>
                <input type="hidden" name="passCode" value = <?php echo $arraypassCode[2];?>>
                <?php echo $arrayPassName[2];?>
            </td>
            <td>
                <?php echo $arrayPassPrice[2];?>원
            </td>
            <td>
                <input type="submit" value="선택" formaction="http://bookdatabase.dothome.co.kr/userPass.php">
            </td>
        </tr>
        </table>
    </form>
</body>
</html>