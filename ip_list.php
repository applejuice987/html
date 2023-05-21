<?php

    // error_reporting( E_ALL );
    // ini_set( "display_errors", 1 );

$dbcon = new mysqli("localhost","bong","1234","project");     

$sql = "select count(*) from ip_table where is_malicious = 'T'";

$result = mysqli_query($dbcon, $sql);

$array = mysqli_fetch_array($result);

$num = $array[0];

        
        






/* paging : 한 페이지 당 데이터 개수 */
$list_num = 5;

/* paging : 한 블럭 당 페이지 수 */
$page_num = 10;

/* paging : 현재 페이지 */
$page = isset($_GET["page"])? $_GET["page"] : 1;

/* paging : 전체 페이지 수 = 전체 데이터 / 페이지당 데이터 개수, ceil : 올림값, floor : 내림값, round : 반올림 */
$total_page = ceil($num / $list_num);
// echo "전체 페이지 수 : ".$total_page;

/* paging : 전체 블럭 수 = 전체 페이지 수 / 블럭 당 페이지 수 */
$total_block = ceil($total_page / $page_num);

/* paging : 현재 블럭 번호 = 현재 페이지 번호 / 블럭 당 페이지 수 */
$now_block = ceil($page / $page_num);

/* paging : 블럭 당 시작 페이지 번호 = (해당 글의 블럭번호 - 1) * 블럭당 페이지 수 + 1 */
$s_pageNum = ($now_block - 1) * $page_num + 1;
// 데이터가 0개인 경우
if($s_pageNum <= 0){
    $s_pageNum = 1;
};

/* paging : 블럭 당 마지막 페이지 번호 = 현재 블럭 번호 * 블럭 당 페이지 수 */
$e_pageNum = $now_block * $page_num;
// 마지막 번호가 전체 페이지 수를 넘지 않도록
if($e_pageNum > $total_page){
    $e_pageNum = $total_page;
};

/* paging : 시작 번호 = (현재 페이지 번호 - 1) * 페이지 당 보여질 데이터 수 */
$start = ($page - 1) * $list_num;

$dbcon = new mysqli("localhost","bong","1234","project");

/* paging : 쿼리 작성 - limit 몇번부터, 몇개 */
$sql = "select * from ip_table where is_malicious = 'T' limit $start, $list_num;";

/* paging : 쿼리 전송 */
$result = mysqli_query($dbcon, $sql);

/* paging : 글번호 */
$cnt = $start + 1;
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Insert title here</title>
    </head>
    <body>
        <h1>
            접근 시도하였던 유해 ip리스트
        </h1>

        <table width=300 cellpadding=1 cellspacing=0>

        <tr class="list-head">
            <td><b>번호</b></td>
            <td><b>시간</b></td>
            <td><b>IP address</b></td>
            <td><b>is_malicious</b></td>
            <td><b>is_vpn</b></td>
            <td><b>can_remote_access</b></td>
            <td colspan="6" height="1"></td>
        </tr>
        <?php

        while($array = mysqli_fetch_array($result)){

        ?>
        <tr class="brd">
            <!-- <td><?php echo $i; ?></td> -->
            <td><?php echo $cnt; ?></td>
            <td><?php echo $array["time"]; ?></td>
            <td><?php echo $array["ip_str"]; ?></td>
            <td><?php echo $array["is_malicious"]; ?></td>
            <td><?php echo $array["is_vpn"]; ?></td>
            <td><?php echo $array["can_remote_access"]; ?></td>
        </tr>

        <?php  
            /* $i++; */
            /* paging */
            $cnt++;
        }; 
        ?>
        </table>
        <div class="page-navi">
            <div class ="page-prev">
    <?php
        /* paging : 이전 페이지 */
        if($page > 1){
    ?>
        <a href="adminpage.php?forward=0&page=1">처음</a>
        <a href="adminpage.php?forward=0&page=<?php echo ($page-1); ?>">이전</a>
        <?php } else{ ?>
            <a>처음</a><a>이전</a>
        <?php }; ?>
        </div>
        <div class = "page-number">
    <?php

    /* pager : 페이지 번호 출력 */
    for($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++){

    ?>
    
    <a href="adminpage.php?forward=0&page=<?php echo $print_page; ?>"><?php echo $print_page; ?></a>
    <?php };?>
        </div>
        <div class = "page-next">

    <?php
    /* paging : 다음 페이지 */
    if($page < $total_page){
    ?>
    <a href="adminpage.php?forward=0&page=<?php echo $page+1; ?>">다음</a>
    <a href='adminpage.php?forward=0&page=<?php echo $total_page; ?>'>마지막</a>
    <?php } else{ ?>
        <a>다음</a><a>마지막</a>
    <?php };?>

        </div>
        <div>
            <span> <?php echo($page) ?> / <?php echo($total_page) ?></span>
        </div>
    </div>

       
    </body>
</html>