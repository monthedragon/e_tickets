<?
$path = '/var/www/html/template/application/third_party/php/pear';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$_SERVER['DOCUMENT_ROOT'] .= '/template';
include_once($_SERVER['DOCUMENT_ROOT']. '/application/third_party/Spreadsheet/Excel/Writer.php');
// Creating a workbook
$workbook = new Spreadsheet_Excel_Writer();
$workbook->setVersion(8);

$yellowHighlight =& $workbook->addFormat();
$yellowHighlight->setFgColor('yellow');
#$yellowHighlight->setBorder(1);

$boldHeader =& $workbook->addFormat();
$boldHeader->setBold();  
//$boldHeader->setBorder(1);  


$border =& $workbook->addFormat();
//$border->setBorder(1);  

$col = 1;
$lblCol = 0;
$rowHeight = 17.25;
foreach($details as $d){
	$row =0;
	//max sheet name is 31
	$setName = substr($d['c_firstname'] . '_' . $d['c_lastname'].'_'.$d['id'],0,30);
	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet($setName);
	$worksheet->setColumn(0,1,60);
	$worksheet->fitToPages (1,1);
	$worksheet->setZoom(100);
	$worksheet->setMargins_LR(0.44,0.44);
	$worksheet->setMargins_TB(0.44,0.44);

    $date = substr($d['calldate'],0,10);
    $enrolled_date = date('F d, Y',strtotime($date));

    $worksheet->write($row, $lblCol,  'DATE',$border);
	$worksheet->write($row, $col,  $enrolled_date,$boldHeader);
	$worksheet->write($row++, $lblCol+2,  'ANNUAL FEE WAIVED FOR LIFE',$boldHeader);
	
	
	//09182014	
    $worksheet->write($row, $lblCol,  'PRI_ANO AS PER MASTER FILE',$border);
	$worksheet->write($row, $col,  $d['sv_pri_no'],$border);
	
	$worksheet->write($row, $lblCol+2,  'GOLD',$boldHeader);
	$worksheet->write($row++, $lblCol+3,  'VISA',$boldHeader);
	
	//09182014
    $worksheet->write($row, $lblCol,  'CHS_ANO AS PER MASTER FILE',$border);
	$worksheet->write($row++, $col,  $d['sv_ch_no'],$boldHeader);
 
    $worksheet->write($row, $lblCol,  'SOURCE CODE',$border);
	$worksheet->write($row++, $col,  $d['sv_source_code'],$boldHeader);

    $worksheet->write($row, $lblCol,  'CARD TYPE REQUESTED BY CLIENT',$border);
    $worksheet->write($row++, $col,  $d['sv_card_request_by_client'],$boldHeader);

	//$worksheet->write($row, $lblCol,  'CARD TYPE');
	//$worksheet->write($row++, $col,  $d['c_card_type'],$border);

	$worksheet->write($row++, $lblCol,  'NAME OF APPLICANT',$yellowHighlight);
//	$worksheet->write($row, $lblCol,  'NAME');
//	$worksheet->write($row++, $col,  $d['c_firstname'] . ' ' . $d['c_lastname'],$border);

	$worksheet->write($row, $lblCol,  'LASTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_lastname'],$border);

	$worksheet->write($row, $lblCol,  'FIRSTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_firstname'],$border);

	$worksheet->write($row, $lblCol,  'MIDDLENAME',$border);
	$worksheet->write($row++, $col,  $d['c_middlename'],$border);

	$worksheet->writeString($row, $lblCol,  'NAME TO APPEAR ON THE CARD',$border);
	$worksheet->write($row++, $col,  $d['c_name_in_card'],$border);
	
	//to add page break make it sure dont use the fittopage
	//$worksheet->setHPageBreaks($row);
	

	$worksheet->write($row, $lblCol,  'DATE OF BIRTH',$border);
	$worksheet->write($row++, $col,  $d['c_dob'],$border);

	$worksheet->write($row, $lblCol,  'GENDER',$border);
	$worksheet->write($row++, $col,  $d['c_gender'],$border);

	$worksheet->write($row, $lblCol,  'CIVIL STATUS',$border);
	$worksheet->write($row++, $col,  $d['c_civil_status'],$border);

	$worksheet->write($row, $lblCol,  'NATIONALITY',$border);
	$worksheet->write($row++, $col,  $d['c_nationality'],$border);

    $worksheet->write($row, $lblCol,  'IF US CITIZEN (US TIN )',$border);
    $worksheet->write($row++, $col,  $d['c_us_tin'],$border);

	$worksheet->write($row, $lblCol,  'PLACE OF BIRTH',$border);
	$worksheet->write($row++, $col,  $d['c_place_of_birth'],$border);

	$worksheet->write($row, $lblCol,  'HOME PHONE NUMBER',$border);
	$worksheet->write($row++, $col,  $d['c_homeno'],$border);

    $worksheet->write($row, $lblCol,  'MOBILE NUMBER',$border);
    $worksheet->write($row++, $col,  $d['c_mobileno'],$border);

    $worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
    $worksheet->write($row++, $col,  $d['c_email_address'],$border);

	/**MOTHERS'info*/
	$worksheet->write($row++, $lblCol,  'MOTHER\'S FULL MAIDEN NAME',$yellowHighlight);
	$worksheet->write($row, $lblCol,  'LASTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_mother_lastname'],$border);

	$worksheet->write($row, $lblCol,  'FIRSTNAME',$border);
	$worksheet->write($row++, $col,  $d['c_mother_firstname'],$border);

	$worksheet->write($row, $lblCol,  'MIDDLE NAME',$border);
	$worksheet->write($row++, $col,  $d['c_mother_middlename'],$border);

	/*PRESENT HOME ADDRESS*/
	$worksheet->write($row++, $lblCol,  'PRESENT HOME ADDRESS',$yellowHighlight);
	$worksheet->write($row, $lblCol,  'NO. STREET,SUBDIVISION,CITY',$border);
	$worksheet->write($row++, $col, ( $d['c_present_add1'] . ' ' . $d['c_present_add2'] . ' ' . $d['c_present_add3'] . ' ' .  $d['c_present_city'] ) );

	$worksheet->write($row, $lblCol,  'ZIP CODE',$border);
	$worksheet->write($row++, $col,  $d['c_present_zip'],$border);


	/*HOME OWNERSHIP*/
	$worksheet->write($row++, $lblCol,  'HOME OWNERSHIP',$yellowHighlight);

	$worksheet->write($row, $lblCol,  'YEARS OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_year_stay'],$border);

	$worksheet->write($row, $lblCol,  'MONTH OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_month_stay'],$border);

	foreach($ownership as $k=>$luDetail){
		$worksheet->write($row, $lblCol,  strtoupper($luDetail['lu_desc']));
		$worksheet->write($row++, $col,  ($d['c_home_ownership'] == $luDetail['lu_code']) ? 'X' : '');
	}


    $worksheet->write($row, $lblCol,  'HAVE YOU STAYED IN THE USA FOR 180 DAYS IN THE LAST 3 YRS (YES/NO)',$border);
    $worksheet->write($row++, $col,  $d['c_stayed_in_us'],$border);


    /*DO YOU OWN A CAR*/
    $worksheet->write($row++, $lblCol,  'DO YOU OWN A CAR',$yellowHighlight);

    foreach($yesno as $k=>$luDetail){
        $worksheet->write($row, $lblCol,  strtoupper($luDetail['lu_desc']));
        $worksheet->write($row++, $col,  ($d['c_is_own_car'] == $luDetail['lu_code']) ? $luDetail['lu_desc'] : '',$border);
    }

	$worksheet->write($row, $lblCol,  'HOW MANY',$border);
	$worksheet->write($row++, $col,  $d['c_how_many_car'],$border);

	foreach($car_ownership as $k=>$luDetail){
		$worksheet->write($row, $lblCol,  strtoupper($luDetail['lu_desc']));
		$worksheet->write($row++, $col,  ($d['c_car_ownership'] == $luDetail['lu_code']) ? 'X' : '',$border);
	}

	//08242014
	$worksheet->write($row, $lblCol,  'NO.OF DEPENDENT',$border);
	$worksheet->write($row++, $col,  $d['c_no_of_dep'],$border);
	
	
	/*TIN GSIS SSS*/
	$worksheet->write($row, $lblCol,  'TIN',$border);
	$worksheet->write($row++, $col,  $d['c_tin'],$border);

	$worksheet->write($row, $lblCol,  'SSS/GSIS',$border);
	$worksheet->write($row++, $col,  $d['c_sss_gsis'],$border);

    //$worksheet->write($row, $lblCol,  'EMAIL ADDRESS');
	//$worksheet->write($row++, $col,  $d['c_email_address'],$border);


    $worksheet->write($row, $lblCol,  'EDUCATIONAL ATTAINMENT',$border);
	$educAttain = '';
	foreach($education_attainment as $k=>$luDetail){
		if($d['c_education_attain'] == $luDetail['lu_code']){
			$educAttain =  $luDetail['lu_desc'];
			break;
		}
		
	}
	$worksheet->write($row++, $col,  $educAttain);
	

	/*PERMANENT HOME ADDRESS*/
	$worksheet->write($row++, $lblCol,  'PERMANENT HOME ADDRESS',$yellowHighlight);
	$worksheet->write($row, $lblCol,  'NO. STREET,SUBDIVISION,CITY',$border);
	$worksheet->write($row++, $col,  $d['c_perma_add1'] . ' ' . $d['c_perma_add2'] . ' ' . $d['c_perma_add3'] . ' ' .  $d['c_perma_city']  );

	$worksheet->write($row, $lblCol,  'ZIP CODE',$border);
	$worksheet->write($row++, $col,  $d['c_perma_zip'],$border);

	/*FINANCIAL STATUS*/
	$worksheet->write($row++, $lblCol,  'FINANCIAL STATUS',$yellowHighlight);
	$worksheet->write($row, $lblCol,  'EMPLOYMENT',$border);
	$worksheet->write($row++, $col,  $d['c_employment'],$border);

    $worksheet->write($row, $lblCol,  'NATURE OF BUSINESS',$border);
    $worksheet->write($row++, $col,  $d['c_comp_nature_bus'],$border);

    $worksheet->write($row, $lblCol,  'COMPANY NAME',$border);
    $worksheet->write($row++, $col,  $d['c_company_name'],$border);

	/*COMPANY ADDRESS*/
	$worksheet->write($row++, $lblCol,  'COMPANY ADDRESS',$yellowHighlight);
	$worksheet->write($row, $lblCol,  'DEPT.,FLR.,BLDG., NO.,STREET.,SUBD.,CITY',$border);
	$worksheet->write($row++, $col,  $d['c_comp_add1'] . ' ' . $d['c_comp_add2'] . ' ' . $d['c_comp_add3'] . ' ' .  $d['c_comp_city'] ,$border );

	$worksheet->write($row, $lblCol,  'ZIP CODE',$border);
	$worksheet->write($row++, $col,  $d['c_comp_zip'],$border);

	/*TOTAL NO. OF YEARS*/
	$worksheet->write($row++, $lblCol,  'TOTAL NO. OF YEARS',$yellowHighlight);

	$worksheet->write($row, $lblCol,  'YEARS OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_comp_year_stay'],$border);

	$worksheet->write($row, $lblCol,  'MONTHS OF STAY',$border);
	$worksheet->write($row++, $col,  $d['c_comp_month_stay'],$border);

	$worksheet->write($row, $lblCol,  'OFFICE PHONE NUMBER',$border);
	$worksheet->write($row++, $col,  $d['c_comp_phone'],$border);

	$worksheet->write($row, $lblCol,  'FAX NUMBER',$border);
	$worksheet->write($row++, $col,  $d['c_comp_fax'],$border);

//	$worksheet->write($row, $lblCol,  'NATURE OF BUSINESS');
//	$worksheet->write($row++, $col,  $d['c_comp_nature_bus'],$border);
//
	$worksheet->write($row, $lblCol,  'OCCUPATION/POSITION',$border);
	$worksheet->write($row++, $col,  $d['c_occupation_pos'],$border);

	$worksheet->write($row, $lblCol,  'TOTAL GROSS ANNUAL INCOME/SOURCE OF FUND (PER ANNUM)',$border);
	$worksheet->write($row++, $col,  $d['c_tgai_souce_fund'],$border);

	/*CARD DETAILS*/
    $worksheet->write($row++, $lblCol,  'CARD DETAILS',$yellowHighlight);
		
	$worksheet->write($row, $lblCol,  'ISSUER',$yellowHighlight);
	$worksheet->write($row, $lblCol+1,  'CARD NO.',$yellowHighlight);
	$worksheet->write($row, $lblCol+2,  'CREDIT LIMIT',$yellowHighlight);
	$worksheet->writestring($row++, $lblCol+3,  'ISSUE DATE',$yellowHighlight);
	
	
	
	$cardCtr = 0;
	//RCBC CARD  only
	$worksheet->write($row++, $lblCol,  'RCBC BANKARD CREDIT CARDS',$border);
	
	if(isset($cards[$d['id']]['RCBC'])){
		foreach($cards[$d['id']]['RCBC'] as $issuer=>$card){

			$worksheet->write($row,$lblCol,$card['issuer'],$border);
			$worksheet->write($row, $col,  $card['card_no'],$border);
			$worksheet->write($row, $col+1,  $card['credit_limit'],$border);
			$worksheet->writestring($row++, $col+2,  $card['issue_date'],$border);

		}
		unset($cards[$d['id']]['RCBC']);
	}
		
	$row++;
	//OTHER than RCBC	
	$worksheet->write($row++, $lblCol,  'ISSUER',$border);
	$worksheet->write($row++, $lblCol,  'OTHER CARDS',$border);
	
	if(isset($cards[$d['id']])){
		foreach($cards[$d['id']] as $issuers){
			
			foreach($issuers as $issuer=>$card){
			
				$worksheet->write($row,$lblCol,$card['issuer'],$border);
				$worksheet->write($row, $col,  $card['card_no'],$border);
				$worksheet->write($row, $col+1,  $card['credit_limit'],$border);
				$worksheet->writestring($row++, $col+2,  $card['issue_date'],$border);

			}
		}

	}


	$row+=2;
	//$worksheet->setHPageBreaks($row);

    /*PREFERRED BILLING ADDRESS*/
    $worksheet->write($row++, $lblCol,  'PREFERRED BILLING ADDRESS',$yellowHighlight);

	foreach($billingaddress as  $k=>$luDetail){
        $worksheet->write($row, $lblCol,  ($luDetail['lu_desc']));
        $worksheet->write($row++, $col,  ($d['c_bill_add'] == $luDetail['lu_code']) ? 'X' : '');
    }


    $worksheet->write($row, $lblCol,  'LOCATION LANDMARK',$border);
	$worksheet->write($row++, $col,  $d['c_landmark'],$border);

    $worksheet->write($row, $lblCol,  'FULLNAME OF AUTORIZED REPRESENTATIVE(LAST,FIRST,MIDDLE)',$border);
	$worksheet->write($row++, $col,  $d['c_auth_firstname'] . ' ' . $d['c_auth_middlename'] . ' ' . $d['c_auth_lastname'],$border);

    $worksheet->write($row, $lblCol,  'CONTACT NO.',$border);
	$worksheet->write($row++, $col,  $d['c_auth_contact_no'],$border);

    $worksheet->write($row, $lblCol,  'MOBILE NO.',$border);
	$worksheet->write($row++, $col,  $d['c_auth_mob_no'],$border);

    $worksheet->write($row, $lblCol,  'E-STATEMENT VIA EMAIL',$border);
	$worksheet->write($row++, $col,   isset($yesnoLU[$d['c_is_e_statement']]) ? strtoupper($yesnoLU[$d['c_is_e_statement']]) : '',$border );
	

    $worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
    $worksheet->write($row++, $col,  $d['c_email_address'],$border);

    $worksheet->write($row, $lblCol,  'WEB SHOPPER (YES OR NO)',$border);
    $worksheet->write($row++, $col,  isset($yesnoLU[$d['c_is_web_shopper']]) ? strtoupper($yesnoLU[$d['c_is_web_shopper']]) : '',$border );

   // $worksheet->write($row, $lblCol,  'Enroll for E-SOA (YES OR NO)');
    //$worksheet->write($row++, $col,  isset($yesnoLU[$d['c_esoa']]) ? strtoupper($yesnoLU[$d['c_esoa']]) : '' );


    /*YOUR SPOUSE*/
    $worksheet->write($row++, $lblCol,  'YOUR SPOUSE',$yellowHighlight);

    $worksheet->write($row, $lblCol,  'LAST',$border);
	$worksheet->write($row++, $col,  $d['c_sp_lastname'],$border);

    $worksheet->write($row, $lblCol,  'FIRST',$border);
	$worksheet->write($row++, $col,  $d['c_sp_firstname'],$border);

    $worksheet->write($row, $lblCol,  'MIDDLE NAME',$border);
	$worksheet->write($row++, $col,  $d['c_sp_middlename'],$border);

    $worksheet->write($row, $lblCol,  'DATE OF BIRTH',$border);
	$worksheet->write($row++, $col,  $d['c_sp_dob'],$border);

	//08242014
	$spEmployment='';
	foreach($employment as $k=>$luDetail){
        //$worksheet->write($row, $lblCol,  $luDetail['lu_desc'],$border);
		//$worksheet->write($row++, $col,  ($d['c_sp_employment_sta'] == $luDetail['lu_code']) ? 'YES' : '');
		if($d['c_sp_employment_sta'] == $luDetail['lu_code']){
			$spEmployment =$luDetail['lu_desc'];
		}
    }
	
	//009232014
	$worksheet->write($row, $lblCol,  'SPOUSE EMPLOYMENT',$border);
	$worksheet->write($row++, $col,  $spEmployment);
	
	$worksheet->write($row, $lblCol,  'PRESENT ADDRESS',$border);
	$worksheet->write($row++, $col, ( $d['c_present_add1'] . ' ' . $d['c_present_add2'] . ' ' . $d['c_present_add3'] . ' ' .  $d['c_present_city'] ),$border );


    /*OTHERS*/
   // $worksheet->write($row++, $lblCol,  'OTHERS',$yellowHighlight);

   // $worksheet->write($row, $lblCol,  'OTHERS');
	//$worksheet->write($row++, $col,  $d['c_sp_employment_other']);

    $worksheet->write($row, $lblCol,  'TELEPHONE NUMBER',$border);
	$worksheet->write($row++, $col,  $d['c_sp_homeno'],$border);

    $worksheet->write($row, $lblCol,  'MOBILE NO.',$border);
	$worksheet->write($row++, $col,  $d['c_sp_mobile_no'],$border);

    $worksheet->write($row, $lblCol,  'COMPANY NAME',$border);
	$worksheet->write($row++, $col,  $d['c_sp_company_name'],$border);

    $worksheet->write($row, $lblCol,  'COMPANY ADDRESS',$border);
	$worksheet->write($row++, $col,  $d['c_sp_company_add'],$border);

    $worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
	$worksheet->write($row++, $col,  $d['c_sp_email_add'],$border);

    $worksheet->write($row, $lblCol,  'OCCUPATION/POSITION',$border);
	$worksheet->write($row++, $col,  $d['c_sp_occupation_pos'],$border);

    $worksheet->write($row, $lblCol,  'TOTAL GROSS ANNUAL INCOME/SOURCE OF FUND (PER ANNUM)',$border);
	$worksheet->write($row++, $col,  $d['c_sp_tgai_source_fund'],$border);


	/*SUPPLEMENTARY CARDS*/

	//add two slots if no supple!
	if(!isset($supple[$d['id']])){
		$supple[$d['id']] = array();
	}
	
    if(isset($supple[$d['id']])){
		for($i=count($supple[$d['id']]);$i<2;$i++){
			$supple[$d['id']][] = true;
		}
		
        $suppleCtr = 0;
		foreach($supple[$d['id']] as $sup){

           // if($suppleCtr > 0)$row+=1;

			$worksheet->write($row++, $lblCol,  'SUPPLEMENTARY CARDS '.($suppleCtr+1),$yellowHighlight);
            $worksheet->write($row, $lblCol,  'LAST',$border);
			$worksheet->write($row++, $col,  $sup['lastname'],$border);

            $worksheet->write($row, $lblCol,  'FIRST',$border);
			$worksheet->write($row++, $col,  $sup['middlename'],$border);

            $worksheet->write($row, $lblCol,  'MIDDLE NAME',$border);
			$worksheet->write($row++, $col,  $sup['firstname'],$border);

            $worksheet->write($row, $lblCol,  'DATE OF BIRTH',$border);
			$worksheet->write($row++, $col,  $sup['dob'],$border);

            $worksheet->write($row, $lblCol,  'GENDER',$border);
			$worksheet->write($row++, $col,  strtoupper($sup['gender']));

            $worksheet->write($row, $lblCol,  'RELATIONSHIP TO THE PRINCIPAL',$border);
			$worksheet->write($row++, $col,  isset($relationship[($sup['relationship'])]) ? $relationship[$sup['relationship']] : '');

            $worksheet->write($row, $lblCol,  'PLACE OF BIRTH',$border);
			$worksheet->write($row++, $col,  $sup['place_of_birth'],$border);

            $worksheet->write($row, $lblCol,  'NATIONALITY',$border);
			$worksheet->write($row++, $col,  $sup['nationality'],$border);

            $worksheet->write($row, $lblCol,  'HOME PHONE NUMBER',$border);
			$worksheet->write($row++, $col,  $sup['home_no'],$border);

            $worksheet->write($row, $lblCol,  'OFFICE PHONE NO.',$border);
			$worksheet->write($row++, $col,  $sup['office_no'],$border);

            $worksheet->write($row, $lblCol,  'MOBILE NO.',$border);
			$worksheet->write($row++, $col,  $sup['mobile_no'],$border);

            $worksheet->write($row, $lblCol,  'EMPLOYMENT (PRIVATE,SELF-EMPLYED,GOV\'T,RETIRED/UNEMPLOYED)',$border);
			$worksheet->write($row++, $col,  isset($employment[$sup['employment']]) ? $employment[$sup['employment']] : '');

            $worksheet->write($row, $lblCol,  ' COMPANY NAME',$border);
			$worksheet->write($row++, $col,  $sup['comp_name'],$border);

            $worksheet->write($row, $lblCol,  'COMPANY ADDRESS',$border);
			$worksheet->write($row++, $col,  $sup['comp_add'],$border);

            $worksheet->write($row, $lblCol,  'EMAIL ADDRESS',$border);
			$worksheet->write($row++, $col,  $sup['email_add'],$border);

            $worksheet->write($row, $lblCol,  'OCCUPATION/POSITION',$border);
			$worksheet->write($row++, $col,  $sup['occupation_pos'],$border);

            $worksheet->write($row, $lblCol,  'ASSIGNED SPEND LIMIT',$border);
			$worksheet->write($row++, $col,  $sup['assigned_spend_limit'],$border);
            $suppleCtr++;
		}

	}
	
	

		$worksheet->write($row, $lblCol,  'RECOMMENDED CREDIT LIMIT',$border);
		$worksheet->write($row++, $col,  '',$border);

		
		for($rstart=1;$rstart<=$row;$rstart++){
			$worksheet->setRow($rstart,$rowHeight);
		}

	#$col++;
}
// Let's send the file
// sending HTTP headers
#exit();
$workbook->send("Agreement {$startDate} to {$endDate}.xls");
$workbook->close();
?>