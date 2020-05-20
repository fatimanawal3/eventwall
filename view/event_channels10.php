
<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';
$var_value = $_POST['config'];
$eventwallid = filter_input(INPUT_GET, 'SeqNo', FILTER_VALIDATE_INT);

        $link = mysqli_connect('localhost', 'root', '');
        
        if (!$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        $db_selected = mysqli_select_db( $link,'SENTINELX' );
            
        if (!$db_selected)
        {
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;   
            echo "<script type='text/javascript'>alert('" . mysqli_connect_errno() . "');</script>";
                 
            exit;
        }

$db = getDbInstance();
$seq = array(); 
?>

<?php
        $sqlu="";
        $sqlu = "SELECT Name,Data FROM eventwallgroups WHERE Event_id='$eventwallid'";
        $result=mysqli_query($link,$sqlu);

        $ix = 0 ;
        $name[$ix] = "" ;
        $data[$ix] = "" ;
               

        while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
        { 
            $name[$ix] = $row[0] ;
            $data[$ix] = $row[1] ; 
            $ix++;
        }

        $data_array = array();
        $event_grp = array();
        $time_grp = array();
                 
        $data2 = explode("-",$data[0]);
                
        for($i=0;$i<sizeof($data2)-1;$i++)
        { 
            $comma_split = explode(",",$data2[$i]);
            $status = $comma_split[3];
            if($status == "1")
            {
                array_push($data_array, $comma_split[0]);
                array_push($event_grp, $comma_split[2]);
                array_push($time_grp, $comma_split[1]);
            }               
        }

       
        $event_array = array();          
        for($j=0;$j<sizeof($event_grp);$j++)
        {
            $e_grp = $event_grp[$j];
            $sqlu1="";
            $sqlu1 = "SELECT Data FROM eventgroups WHERE SeqNo='$e_grp'";
            $result=mysqli_query($link,$sqlu1);
            $ix1= 0 ;              
            $edata[$ix1] = "" ;
               
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
            { 
                $edata[$ix1] = $row[0] ;
                $ix1++;
            }

            $edata2 = explode(",",$edata[0]);

            for($i=0;$i<sizeof($edata2)-1;$i++)
            {
                $ecomma_split = explode("-",$edata2[$i]);
                $status = $ecomma_split[1];
                if($status == "1")
                {
                   array_push($event_array, $ecomma_split[0]);
                }
            }
        }


        $time_array = array();
        for($j=0;$j<sizeof($time_grp);$j++)
        {
            $t_grp = $time_grp[$j];
            $sqlu2="";
            $sqlu2 = "SELECT TData FROM time_groups WHERE Seq_no='$t_grp'";
            $result=mysqli_query($link,$sqlu2);
            $ix2= 0 ;
            $tdata[$ix2] = "" ;
               
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
            { 
                $tdata[$ix2] = $row[0] ;
                $ix2++;
            }

            $tdata2 = explode(",",$tdata[0]);

            for ($i=0; $i<sizeof($tdata2)-1 ; $i++)
            { 
                $split = explode("-",$tdata2[$i]);
                if($split[0] != "" and $split[1]!="")
                {
                    $start=0;
                    $end=0;
                    $t=0;
                    $start = strtotime($split[0]);
                    $t= time();
                    $end = strtotime($split[1]);
                    
                    if($start<=$t and $t<=$end)
                    {
                        array_push($time_array, $data_array[$j]); 
                    }
                } 
            }
        }


        $sqlu3="";
        $sqlu3 = "SELECT seq_no,channel_id,unixtime,event,Event_id,image FROM dl_event_tab order by seq_no desc limit 150";
        $result=mysqli_query($link,$sqlu3);
        $ix3 = 0 ;

        $seq_no[$ix3] = "" ;
        $channel_id[$ix3] = "" ;
        $unixtime[$ix3] = "" ;
        $event[$ix3] = "" ;
        $eventid[$ix3] = "" ;
        $image[$ix3] = "" ;

        while ($row = mysqli_fetch_array($result, MYSQLI_NUM))
        { 
            $seq_no[$ix3] = $row[0] ;
            $channel_id[$ix3] = $row[1] ;
            $unixtime[$ix3] = $row[2];
            $event[$ix3] = $row[3];
            $eventid[$ix3] = $row[4];
            $image[$ix3] = $row[5];
            $ix3++;
        }
?> 

<?php 
        for($i=0;$i<sizeof($channel_id);$i++){
            for($j=0;$j<sizeof($data_array);$j++){
                if($data_array[$j]==$channel_id[$i]){

                    for($l=0;$l<sizeof($event_array);$l++){
                        if($event_array[$l]==$eventid[$i]){
                                
                            for($m=0;$m<sizeof($time_array);$m++){
                                if($time_array[$m]==$channel_id[$i]){
                                    array_push($seq, $seq_no[$i]);
        }}}}}}}
?>


<?php include BASE_PATH . '/includes/header.php'; ?>

<div id="page-wrapper">
<?php include BASE_PATH . '/includes/flash_messages.php'; ?>

<?php 
   if(sizeof($seq)>=9)
    {
        $size = $var_value;
    }
    else
    {
        $size = sizeof($seq);
    }?>

<?php
    if($var_value==16) 
        for($i=0;$i<$size;$i++){
            for($j=$i;$j<150;$j++){
                if($seq_no[$j]==$seq[$i]){?><a onclick="window.open('popup.php?id=<?php echo $seq_no[$j];?>', '_blank', 'location=no,height=300,width=500,scrollbars=no,status=no toolbar=no, menubar=no');"><?php
                echo '<img alt="Image" height="150px" width="150px"  style="margin-right: 100px;margin-bottom: 20px;" src="data:image/jpeg;base64,'.base64_encode($image[$j]).'"/>';?></a>
<?php }}}?>

<?php
    if($var_value==9) 
    for($i=0;$i<$size;$i++){
        for($j=$i;$j<150;$j++){
            if($seq_no[$j]==$seq[$i]){?><a onclick="window.open('popup.php?id=<?php echo $seq_no[$j];?>', '_blank', 'location=no,height=300,width=500,scrollbars=no,status=no toolbar=no, menubar=no');"><?php
                echo '<img alt="Image"  height="150px" width="150px"  style="margin-right: 150px;margin-bottom: 20px;" src="data:image/jpeg;base64,'.base64_encode($image[$j]).'"/>';?> </a>
<?php }}} ?>

<?php
    if($var_value==4) 
    for($i=0;$i<$size;$i++){
        for($j=$i;$j<150;$j++){
            if($seq_no[$j]==$seq[$i]){?><a onclick="window.open('popup.php?id=<?php echo $seq_no[$j];?>', '_blank', 'location=no,height=300,width=500,scrollbars=no,status=no toolbar=no, menubar=no');"><?php
                echo '<img alt="Image" height="150px" width="150px"  style="margin-right: 50px;margin-bottom: 20px;margin-left: 150px;" src="data:image/jpeg;base64,'.base64_encode($image[$j]).'"/>';?></a>
<?php }}}?>

<?php
    if($var_value==25) 
    for($i=0;$i<$size;$i++){
        for($j=$i;$j<150;$j++){
            if($seq_no[$j]==$seq[$i]){?><a onclick="window.open('popup.php?id=<?php echo $seq_no[$j];?>', '_blank', 'location=no,height=300,width=500,scrollbars=no,status=no toolbar=no, menubar=no');"><?php
                echo '<img alt="Image" height="150px" width="150px"  style="margin-right: 50px;margin-bottom: 20px;" src="data:image/jpeg;base64,'.base64_encode($image[$j]).'"/>';?> </a>
<?php }}} ?>
<?php
    if($var_value==36) 
    for($i=0;$i<$size;$i++){
        for($j=$i;$j<150;$j++){
            if($seq_no[$j]==$seq[$i]){?><a onclick="window.open('popup.php?id=<?php echo $seq_no[$j];?>', '_blank', 'location=no,height=300,width=500,scrollbars=no,status=no toolbar=no, menubar=no');"><?php
                echo '<img alt="Image" height="150px" width="150px"  style="margin-right: 10px;margin-bottom: 20px;" src="data:image/jpeg;base64,'.base64_encode($image[$j]).'"/>';?> </a>
<?php }}} ?>

<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th>Seq_no</th>
            <th>channel id</th>
            <th>event</th>
            <th>event id</th>
            <th>unixtime</th>
            <th>image</th>
         </tr>
    </thead>
    <tbody>

        <?php 
        for($i=0;$i<$var_value;$i++){
            for($j=0;$j<150;$j++){
                if(!(empty($seq))){
               if($seq_no[$j]==$seq[$i]){?>
        <tr>                     
                <td><?php echo $seq_no[$j];?></td>
                <td><?php echo $channel_id[$j];?></td>
                <td><?php echo $event[$j];?></td>
                <td><?php echo $eventid[$j];?></td>
                <td><?php echo $unixtime[$j];?></td>
                <td><?php echo '<img alt="Image" height="150px" width="150px" src="data:image/jpeg;base64,'.base64_encode($image[$j]).'"/>'; ?></td>
        </tr>
        <?php }}}}?>
    </tbody>
</table>
   
</div>
<?php include BASE_PATH . '/includes/footer.php'; ?>
