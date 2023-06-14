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
</body>
</html>

    <?php
            if(!$bookName){
                echo "<script>alert('단어를 입력해주세요.')</script>";
                echo "<script>history.back();</script>";
            }
            else{
            
                //DB검색문
                $query = "SELECT * FROM `도서조회이력` WHERE 도서명 LIKE '%$bookName%';";
                
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