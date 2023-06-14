<?php
session_start();
session_destroy();
echo "
        <script>
            location.href='http://bookdatabase.dothome.co.kr/main.php';
        </script>
    ";
?>