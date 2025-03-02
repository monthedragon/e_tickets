<?

//2018-10-16 fcp
function password_strength_check($password, $min_len = 5, $max_len = 70, $req_digit = 1, $req_lower = 1, $req_upper = 1, $req_symbol = 1) {
	// Build regex string depending on requirements for the password
	$regex = '/^';
	if ($req_digit == 1) { $regex .= '(?=.*\d)'; }              // Match at least 1 digit
	if ($req_lower == 1) { $regex .= '(?=.*[a-z])'; }           // Match at least 1 lowercase letter
	if ($req_upper == 1) { $regex .= '(?=.*[A-Z])'; }           // Match at least 1 uppercase letter
	if ($req_symbol == 1) { $regex .= '(?=.*[^a-zA-Z\d])'; }    // Match at least 1 character that is none of the above
	$regex .= '.{' . $min_len . ',' . $max_len . '}$/';

	if(preg_match($regex, $password)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

if(!function_exists('get_ticket_warning'))
{
   // High tickets = 1 day
   // Medium tickets = 5 days
   // Low tickets = 7 days
  function get_ticket_warning($day_lapse,$details,$user_type)
  {
	 $priority = $details['priority'];
	 $status = $details['status'];
	 
	 if($status == 'completed'){
		 //if the ticket is already completed then dont calculate the lapses
		 return false;
	 }
	 
	 if($user_type != $details['send_to']){
		 //since we added from the search view the "include created tickets"
		 //we can now able to get tickets that is already ASSIGNED to a particular department
		 //in that case dont calculate the lapses
		 //only those ticket who is under your department should be calculated
		 return false;
	 }
	 
	$show_alert = false;
	if($priority == 'high' && $day_lapse >= 1){
		$show_alert = true;
	}elseif($priority == 'medium' && $day_lapse >= 5){
		$show_alert = true;
	}elseif($day_lapse >= 7){ //Low
		$show_alert = true;
	}
	
	if($show_alert){
		return 'tr_ticket_lapsed';
	}
	
	return '';
  }
}

if(!function_exists('compute_day_lapse'))
{
  function compute_day_lapse($date)
  {
	$now = time(); // or your date as well
	$your_date = strtotime($date);
	$datediff = $now - $your_date;
	$num_of_days = round($datediff / (60 * 60 * 24));
   return $num_of_days;
  }
}


if(!function_exists('testHelper'))
{
  function testHelper($value)
  {
   return htmlentities($value);
  }
}

if(!function_exists('get_err_msgs'))
{
  function get_err_msgs($ident)
  {
	$message = array(0=>'You still have locked record, please tag it first.',
					 'A'=>'You still have locked record, please tag it first.',
					 1=>'This record is locked to other user, please choose different record.',
					 'B'=>'This record is locked to other user, please choose different record.',
					 'C'=>'No more records!',
					 2=>'User already Exist.',
					 3=>"Old password doesn't match",
					 4=>"New password doesn't match",
					 5=>'Minimum length of PW is 5',
					 6=>'Please select main application file',
					 7=>'Please input name, this will be used for file naming.',
					 'max_virgin'=>"You've already reached the max virgin record per day.",
					 'pw_str'=>"Password should have at least 
								<ul>
									<li>1 digit
									<li>1 lower and 1 uppercase
									<li>1 special character 
									<li> minimum of 5 characters
								</ul>",
					 );
	
	if(isset($message[$ident]))
		return $message[$ident];
	else
		return "Please contact your admin err {$ident}";
   
  }
}


//fx not in use, due to some multiple times of initializing the CI_Session
//check if the user has access to certain point/part of a page
if(!function_exists('has_access'))
{ 
	function has_access($rightID)
	{ 
		$session = new CI_Session();
		var_dump($session->all_userdata());
	}
}


//time log status equivalent
if(!function_exists('time_log_equiv'))
{ 
	function time_log_equiv($timeLog)
	{
		$timeStatus = array(1=>'TIME IN',0=>'TIME OUT');
		return  $timeStatus[$timeLog];
	}
}

if(!function_exists('emailGmail'))
{ 
	function emailGmail($contacts,$attach=''){ 
		$msg = "Please check this error: <br><br>";
		
		foreach($msgs as $err)
			$msg .= $err .'<br><br>';
		
		
		define('GUSER', 'you@gmail.com'); // GMail username
		define('GPWD', 'password'); // GMail password

		$mail = new PHPMailer(true); // create a new object
		
		try {
		
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587 or 465
			$mail->IsHTML(true);// send as HTML
			$mail->Username = "@gmail.com";
			$mail->Password = "password";
			$mail->Subject = "Test"; 
			$mail->From     = "@gmail.com";
			$mail->FromName = "TEST";
			$mail->SetLanguage("en", 'includes/phpMailer/language/');

	 
			$contacts_arr = explode(',',$contacts);
				
			foreach($contacts_arr as $c){
				$mail->AddAddress($c);
			}
		   
			$mail->IsHTML(true);  // send as HTML

			if(is_array($attach)){
				foreach($attach as $file)
					$mail->addAttachment($file);
			}
			
			
			$mail->Subject  =  'Test email!';
			$mail->Body     =  str_replace("\r\n","<br>",$msg); 
			
			if(!$mail->Send())
				$sys_rep .="Mailer Error: " . $mail->ErrorInfo;
			else
				$sys_rep = "Success!";
				
			echo  $sys_rep ; 
			
		}catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		}catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
		} 
		  
	}
}


if(!function_exists('email'))
{ 
	function email($contacts,$attach=''){ 
			$msg = "";
			
			$mail = new PHPMailer();
			$mail->IsSMTP();                                   // send via SMTP                                 
			$mail->Host     = "172.20.70.2"; // SMTP servers
			$mail->SMTPAuth = false;     // turn on SMTP authentication
			$mail->Username = "";  // SMTP username
			$mail->Password = ""; // SMTP password

			$mail->From     = "no-reply@Test.ph";
			$mail->FromName = "Test Mailer";
			
			$contacts_arr = explode(',',$contacts);
				
			foreach($contacts_arr as $c){
				$mail->AddAddress($c);
			}
		   
			$mail->IsHTML(true);  // send as HTML

			if(is_array($attach)){
				foreach($attach as $file)
					$mail->addAttachment($file);
			}
			
			$mail->Subject  =  'Test email!';
			$mail->Body     =  str_replace("\r\n","<br>",$msg); 
			
			if(!$mail->Send())
				$sys_rep .="Mailer Error: " . $mail->ErrorInfo;
			else
				$sys_rep = "Success!";
			echo  $sys_rep ;  
	}
}


//time log status equivalent
if(!function_exists('rpt_header'))
{ 
	function rpt_header($headers,$rpt_presentation)
	{
		$months = array(1=>'January');
		
		echo "<tr>";
		echo "<td></td>";
			foreach($headers as $d){
				if($rpt_presentation == 'DAY_R') //if daily
					$val = date('Md',strtotime($d . ' 00:00:00'));
				 
				if($rpt_presentation == 'MONTH_R')
				{ //if monthly
					$monthNum = $d;
					$val = date("F", mktime(0, 0, 0, $monthNum, 10));
				}
				
				if($rpt_presentation == 'YEAR_R')
					$val = $d;
					
				echo "<td>{$val}</td>";
			}
		echo "</tr>";
	}
}


//SELECT OBJECT
if(!function_exists('select'))
{ 
	function select($id,$name,$class,$value='',$def=null,$misc=null,$options=null)
	{
		$select =  "<select id='{$id}' name='{$name}' class='$class'>";
		$select.= "<option value=''>--select--</option>";
			
			foreach($options as $code=>$desc){

				if($value == '') //use the def
					$selected= (($def==$code) ? 'selected' : '');
				else
					$selected= (($value==$code) ? 'selected' : '');
						
				$select.="<option value='{$code}' {$selected}>{$desc}</option>";
			}
			
		$select.="</select>"; 
		echo $select;
	}
}


//input OBJECT
if(!function_exists('input'))
{ 
	function input($id,$name,$type,$class='',$value='',$def='',$misc='',$size=20,$maxlen=50)
	{
		if(!empty($value))
			$val = $value;
		else
			$val = $def;
			
		$input = "<input type='{$type}'  id='{$id}' name='{$name}' value='{$val}' class='{$class}' {$misc} size=$size maxlength=$maxlen>";
		
		echo $input;
	}
}


//textarea OBJECT
if(!function_exists('textarea'))
{ 
	function textarea($id,$name,$class='',$value='',$def='',$misc='',$rows=5,$cols=90)
	{
		if($value != '')
			$val = $value;
		else
			$val = $def;
			
		$textarea = "<textarea name='$name' id='$id' class='{$class}' rows=$rows cols=$cols $misc>{$val}</textarea>";
		
		echo $textarea;
	}
}


if ( ! function_exists('img'))
{
    function img($src = '', $index_page = FALSE,$width='')
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        // If there is no alt attribute defined, set it to an empty string
        if ( ! isset($src['alt']))
        {
            $src['alt'] = '';
        }

        $img = '<img';

        foreach ($src as $k=>$v)
        {

            if ($k == 'src' AND strpos($v, '://') === FALSE)
            {
                $CI =& get_instance();

                if ($index_page === TRUE)
                {
                    $img .= ' src="'.$CI->config->site_url($v).'"';
                }
                else
                {
                    $img .= ' src="'.$CI->config->slash_item('base_url').$v.'"';
                }
            }
            else
            {
                $img .= " $k=\"$v\"";
            }
        }

        if(!empty($width))
            $img .= " width={$width}px";

        $img .= '/>';

        return $img;
    }
}

?>
