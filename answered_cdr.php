 <?php
/*
   Copyright 2017, https://asterisk-pbx.ru
   
   This file is part of Asterisk Call Center Stats.
    Asterisk Call Center Stats is free software: you can redistribute it 
    and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 3 of the 
    License, or (at your option) any later version.

    Asterisk Call Center Stats is distributed in the hope that it will be 
    useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Asterisk Call Center Stats.  If not, see 
    <http://www.gnu.org/licenses/>.
*/
require_once("config.php");
include("sesvars.php");
//ini_set('display_errors',1);
//error_reporting(E_WARNING);
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Asterisk Call Center Stats</title>
    <style type="text/css" media="screen">@import "css/basic.css";</style>
    <style type="text/css" media="screen">@import "css/tab.css";</style>
    <style type="text/css" media="screen">@import "css/table.css";</style>
    <style type="text/css" media="screen">@import "css/fixed-all.css";</style>
	<script type="text/javascript" src="js/sorttable.js"></script>
</head>
<?php
if(isset($_POST['pagerows'])) {
   $page_rows = $_POST['pagerows'];
   $_SESSION['QSTATS']['pagerows']=$page_rows;
} else {
   $_SESSION['QSTATS']['pagerows'] = 100;
}

if ( (isset($_POST['callerid_search'])) && (strlen($_POST['callerid_search']) > 0) ) {
    $callerid_search = $_POST['callerid_search'];
    $sql = "select distinct(callid) from $DBTable where time >= '$start' AND time <= '$end' and data2 like '%$callerid_search%'";
    $restemp = mysqli_query($connection, $sql);
        foreach($restemp as $temp){
		$callid_search .= ",'".$temp['callid']."'";
		}	
    $callid_search = substr($callid_search, 1);	
    $sql = "select time, callid, queuename, agent, event, data1, data2, data3 from $DBTable where time >= '$start' AND time <= '$end'
            and event in ('COMPLETECALLER','COMPLETEAGENT','ENTERQUEUE') and callid in ($callid_search) order by callid";
    $rescomplete = mysqli_query($connection, $sql);
} elseif ( (isset($_POST['outagent'])) && (strlen($_POST['outagent']) > 0) ) {
    $outagent = $_POST['outagent'];
    $sql = "select time, callid, queuename, agent, event, data1, data2, data3 from $DBTable where time >= '$start' AND time <= '$end'
            and event in ('COMPLETECALLER','COMPLETEAGENT','ENTERQUEUE')  and agent in ('$outagent','NONE') order by callid";
    $rescomplete = mysqli_query($connection, $sql);
} else {
    $sql = "select time, callid, queuename, agent, event, data1, data2, data3 from $DBTable
           where time >= '$start' AND time <= '$end' AND agent IN ($agent , 'NONE') and queuename in ($queue)
           and event in ('COMPLETECALLER','COMPLETEAGENT','ENTERQUEUE') order by callid, time limit $page_rows";
    $rescomplete = mysqli_query($connection, $sql);
}
mysqli_close($connection);
$start_parts = explode(" ,:", $start);
$end_parts   = explode(" ,:", $end);     
?>

<body>
<?php include("menu.php"); ?>
<div id="main">
    <div id="contents">
		<TABLE width='90%' border=0 cellpadding=0 cellspacing=0>
                <CAPTION><?php echo $lang["$language"]['report_info']?></CAPTION>
                <TBODY>
                <TR>
                    <TD><?php echo $lang["$language"]['queue']?>:</TD>
                    <TD><?php echo $queue?></TD>
                </TR>
                </TR>
                    <TD><?php echo $lang["$language"]['start']?>:</TD>
                    <TD><?php echo $start_parts[0]?></TD>
                </TR>
                </TR>
                <TR>
                    <TD><?php echo $lang["$language"]['end']?>:</TD>
                    <TD><?php echo $end_parts[0]?></TD>
                </TR>
                <TR>
                    <TD><?php echo $lang["$language"]['period']?>:</TD>
                    <TD><?php echo $period?> <?php echo $lang["$language"]['days']?></TD>
                </TR>
                </TBODY>
                </TABLE>
<br />				
<div id="search" align="left">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="frm1" name="frm1" method="post">
&nbsp;&nbsp;&nbsp;<b><?php echo $lang["$language"]['callerid']?>:</b>&nbsp;<input type="text" id="callerid_search" name="callerid_search" />
&nbsp;&nbsp;&nbsp;<b><?php echo $lang["$language"]['agent'];?>:</b>&nbsp;<input type="text" id="outagent" name="outagent"/>
&nbsp;&nbsp;&nbsp;<button type="submit" name="submit"><?php echo $lang["$language"]['search'];?></button>
</form>
</div>			
<div id="rows" align="right">			
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="frm2" name="frm2" method="post">
	&nbsp;&nbsp;&nbsp;<b><?php echo $lang["$language"]['page_rows'];?></b>&nbsp;
<select onchange="this.form.submit()" id="pagerows" name="pagerows">
  <option selected="<?php echo $_SESSION['QSTATS']['pagerows'];?>"><?php echo $_SESSION['QSTATS']['pagerows'] / 2;?></option>
  <option value="100">50</option>
  <option value="200">100</option>
  <option value="1000">500</option>
  <option value="2000">1000</option>
  <option value="20000">10000</option>
</select>
</form>
</div>
<br />				
<a name='5'></a>
           <h3> <?php echo $lang["$language"]['answered_calls']?></h3>
<br />		   
		   <table width='90%' cellpadding=1 cellspacing=1 border=0 class='sortable' id='5' >
        <TR> 
 	   	<TH><?php echo $lang["$language"]['date']?></TH>
		<TH><?php echo $lang["$language"]['callerid']?></TH>
		<TH><?php echo $lang["$language"]['queue']?></TH>
		<TH><?php echo $lang["$language"]['agent']?></TH>					
		<TH><?php echo $lang["$language"]['disconnect_cause']?></TH>
		<TH><?php echo $lang["$language"]['holdtime']?></TH>
		<TH><?php echo $lang["$language"]['calltime']?></TH>
        <TH><?php echo $lang["$language"]['uniqueid']?></TH>
		<TH><?php echo $lang["$language"]['recordfile']?></TH>
       </TR>
<?php
$header_pdf=array($lang["$language"]['time'],$lang["$language"]['callerid'], $lang["$language"]['queue'],$lang["$language"]['agent'],$lang["$language"]['event'],$lang["$language"]['holdtime'],$lang["$language"]['calltime']);
$width_pdf=array(25,23,23,23,23,25,25,20);
$title_pdf=$lang["$language"]['answered_calls'];
$data_pdf = array();

foreach($rescomplete as $row) {
switch($row['event']) {
    case "ENTERQUEUE": 
        $callerid = $row['data2'];
     $break;
    case "COMPLETEAGENT":
        $holdtime = seconds2minutes($row['data1']);
        $calltime = seconds2minutes($row['data2']);
        $time = strtotime($row['time']) - ($row['data1'] + $row['data2']); 
     $break;
    case "COMPLETECALLER":
        $holdtime = seconds2minutes($row['data1']);
        $calltime = seconds2minutes($row['data2']);
        $time = strtotime($row['time']) - ($row['data1'] + $row['data2']); 
     $break;
if ($row['event']=="COMPLETEAGENT") {
        $cause_hangup=$lang["$language"]['agent_hungup'] ;
        } elseif ($row['event']=="COMPLETECALLER") {
        $cause_hangup=$lang["$language"]['caller_hungup'];
        } 	
if ( ($row['event'] == "COMPLETEAGENT") || ($row['event'] == "COMPLETECALLER") ) {
        $page_rows2 += count($row['event']);
	    $tmpError =  $row['callid'] ;
	    $tmpRec = '<audio controls preload="none">
	           <source src="dl.php?f=[_file]">
			   </audio>
			   <a href="dl.php?f=[_file]">' . $row['callid'] . '</a>';
	
	    $rec['filename'] = $row['callid'] . '.mp3';	
	    $rec['path'] = '/home/asterisk/monitor/mp3/'.$rec['filename'];
	
	if (file_exists($rec['path']) && preg_match('/(.*)\.mp3$/i', $rec['filename'])) {
		$tmpRes = str_replace('[_file]', base64_encode($rec['filename']), $tmpRec);
	} else { 
		$tmpRes = $tmpError; 
    }
echo "<TR><TD>" . date('Y-m-d H:i:s', $time) . "</TD>
		  <TD>" . $callerid . "</TD>
	      <TD>" . $row['queuename'] . "</TD>
	      <TD>" . $row['agent'] . "</TD>
	      <TD>" . $cause_hangup . "</TD>
	      <TD>" . $holdtime . "</TD>
	      <TD>" . $calltime . "</TD>
	      <TD>" . $tmpRes. "</TD>
	      </TR>\n";
		$linea_pdf = array($time,$callerid,$row['queuename'],$row['agent'],$cause_hangup,$holdtime,$calltime);
        $data_pdf[]=$linea_pdf;
   }
  }
 }
mysqli_free_result($rescomplete);

print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf);
 ?>
 </table>
<?php 
print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf);
?>
	  <br/>	
     </div>
    </div>
   <div id="footer"><a href='https://asterisk-pbx.ru'>Asterisk-pbx.ru</a> 2017</div>
  </body>
 </html>
