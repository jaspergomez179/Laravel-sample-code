<?php 
	require_once("db.php");
	
	// shorthand code by akash
	require_once("shortcode_api/Googl.class.php");
	$googl = new Googl('AIzaSyDoFcUaREdLurX-ZDAUm_dbWhBWusOvD50');
	function assert_equals($is, $should) {
	  if($is != $should) {
		//exit(1);
	  } else {
		println('Passed!');
	  }
	}
	
	function assert_url($is) {
	  if(!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $is)) {
		//exit(1);
	  } else {
		println('Passed!');
	  }
	}
	
	function println($text) {
	  echo $text . "\n";
	}

	$status_list = array( "NSW" => "Australia/Sydney","WA" => "Australia/Perth", "ACT" => "Australia/Sydney", "VIC" => "Australia/Melbourne", "SA" => "Australia/Adelaide", "QLD" => "Australia/Brisbane", "N/A" => "Australia/Sydney", "TAS" => "Australia/Hobart");
	
	foreach ($status_list as $key => $value)
	{
		date_default_timezone_set($value);
		echo 'now 1 : '; echo $now = date('Y-m-d h:i A');
		echo '<br>';

		//echo 'now  : '; echo $now = "2017-05-27 11:10 AM";
		//echo '<br>';
		echo '10 m : '; echo $min10 = date("Y-m-d h:i A", strtotime('-10 minutes', strtotime($now)));
		echo '<br>';
		//$min10 = "2017-12-20 04:35 PM";
		

		$uri = "https://goo.gl/Yqs4MU";
		echo "<hr><br> 10 min <br>";
		echo $data_q = "SELECT * FROM `data` WHERE `state` IN(SELECT `id_coordinator` FROM `coordinator` WHERE `state_coordinator` = '".$key."') AND CONCAT(date, ' ', endtime) = '".$min10."' AND flag_status!='deleted' ORDER BY `id` DESC";
		
		echo '<br> If Shift End button not pressed by TM';
		$da_q10_1 = mysqli_query($conn,$data_q);
		while ($row10_1 = mysqli_fetch_array($da_q10_1))
		{
			echo $row10_1['id'].'<br>';
			$staff = $row10_1['staff'];
			for ($count=1, $i=1; $count<=$staff ; $count++)
			{
				$val = "staff" . $count;
				$name = $row10_1[$val];
				$confirm_var1 = "confirm". $count;
				$confirm1 =  $row10_1[$confirm_var1];
				$query2 = "SELECT * FROM promostaff WHERE CONCAT(firstname, ' ', phone) = '$name' and status = '0' ;";
				$exec2 = mysqli_query($conn,$query2);
				$row10_1ult = mysqli_fetch_array($exec2);
				$query3 = "SELECT * FROM staff_attendance WHERE data_id = '".$row10_1["id"]."' AND staff_id = '".$row10_1ult["id"]."' AND start_time !='' AND end_time ='' ";
				$exec3 = mysqli_query($conn,$query3);
				$feed1 = mysqli_fetch_array($exec3);
				$noraw_neww = mysqli_num_rows($exec3);
				$view1 = $row10_1['token'];
				$endtime = $row10_1['endtime'];
				$url = "";
				$id1 = $row10_1['id'];
				$jobtype = $row10_1['job'];
				$jobtype_org = $row10_1['jobtype'];

				if($jobtype == "Human Billboard" || $jobtype == "Bike Billboards" || $jobtype == "Video Billboards" || $jobtype == "lettermen" || $jobtype == "Mini Projections" || $jobtype == "Brand Ambassadors" || $jobtype == "Sign Wavers") 
				{
					// echo "CON".$confirm1;
					// echo "NOR".$noraw_neww;
					// echo "ROLE".$row10_1ult['role'];
					if($confirm1=='1' && $noraw_neww > '0')
					{	
						if($confirm1=='1' && $noraw_neww > '0')
					{	
						if($jobtype == "Bike Billboards"){
							$endtime = 
							$endtime = strtotime($raw['starttime']);
							$endtime = strtotime('+10 minutes', $endtime); 
							$endtime = date("h:i a", $endtime);
							echo $phone = $row10_1ult['phone'];
							
							$url = "https://www.streetfightermedia.com.au/shifts/show-".$jobtype_org.".php?view=".$view1."&id=".$id1."&opt=attend";
							$url = $googl->shorten($url);
							
							$messages = array();
							//$txtmsg = " Did you forget to 'End job'. ".$url."\nNo Reply";						
							$txtmsg = "SFM: Don’t forget to login to end today’s job. ".$url." No Reply";						
							$messages[] = array("body"=>$txtmsg, "to"=>$phone);
							// sms
							sendSms($messages);
						}else{
							echo $phone = $row10_1ult['phone'];
							$url = "https://www.streetfightermedia.com.au/shifts/show-".$jobtype_org.".php?view=".$view1."&id=".$id1."&opt=attend";
							$url = $googl->shorten($url);
							
							$messages = array();
							//$txtmsg = " Did you forget to 'End job'. ".$url."\nNo Reply";						
							$txtmsg = "SFM: Don’t forget to login to end today’s job. ".$url." No Reply";						
							$messages[] = array("body"=>$txtmsg, "to"=>$phone);
							// sms
							sendSms($messages);
						}
						// echo $phone = $row10_1ult['phone'];
						// $url = "https://www.streetfightermedia.com.au/shifts/show-".$jobtype_org.".php?view=".$view1."&id=".$id1."&opt=attend";
						// $url = $googl->shorten($url);
						
						// $messages = array();
						// //$txtmsg = " Did you forget to 'End job'. ".$url."\nNo Reply";						
						// $txtmsg = "SFM: Don’t forget to login to end today’s job. ".$url." No Reply";						
						// $messages[] = array("body"=>$txtmsg, "to"=>$phone);
						// // sms
						// sendSms($messages);
					}	
				}		
			}
		}
	}
	

	function sendSms($messages)
	{
		
		require 'public_html/clicksend_php/vendor/autoload.php';
		try {
			
			// Prepare ClickSend client.
			$client = new \ClickSendLib\ClickSendClient('Streetfighter', 'F87EA9B6-0116-A290-9F7D-AFA2FD4D9C8E');
		
			// Get SMS instance.
			$sms = $client->getSMS();
		
			// Send SMS.
			$response = $sms->sendSms(['messages' => $messages]);
			include('sms_log_db.php');
				
		} catch(\ClickSendLib\APIException $e) {
		
			//print_r($e->getResponseBody());
		
		}
	}
?>		