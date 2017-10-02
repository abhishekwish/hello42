<?php
/**
 * Mohd Emadullah, Base Fare table
 */
namespace PointToPoint\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;

class BaseFareTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getFareDetails($city_id, $booking_type_id, $cab_type)
    {  
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('bf'=>'tbl_base_fare'))
               ->columns(array('*'));
        $select->where(array('city_id'=>$city_id));
        $select->where(array('booking_type_id'=>$booking_type_id));
        $select->where(array('cab_type'=>$cab_type));
        $statement = $sql->getSqlStringForSqlObject($select);
        
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray(); 
        
    }    
    
    public function pointBookingCabList($city_id, $booking_type_id, $cab_type, $distance, $pickupTime)
    {
        
        $datam2 = $this->fetchPointToPointData($city_id, $booking_type_id, $cab_type, $distance);
                
        if($datam2!='')
        {
            $totalbill = $datam2['totalbill'];
            $estimated_price_before_markup  =   $datam2['minimum_charge'];				
            if($point_MinimumBookingAmount!="")
            {
                if($totalbill>$point_MinimumBookingAmount)
                {
                    if($point_DiscountType=='RS')
                    {
                        $coupon_amt = $totalbill-$point_coupanDisount;
                    }
                    else if($point_DiscountType=='%')
                    {
                        $coupon_amt = $totalbill-($totalbill*$point_coupanDisount/100);
                    }
                }
            }else{
                $totalbill = $totalbill;
            }
            $PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
            $totalbill	=	$totalbill + $PeakFare['peakcharge'];
            $NightCharges = $this->calculateNigthCharges($pickupTime,$datam2[0]['night_rate_begins'],$datam2[0]['night_rate_ends'],$datam2[0]['night_charge_unit'],$datam2[0]['night_charges'],$totalbill);
                                   
            $totalbill	=	$totalbill + $NightCharges;
            $extraPrice	= $this->calculateExtraCharges($totalbill,$datam2[0]['extras']);
            
            $totalbill	=	$totalbill + $extraPrice;
            
            $BasicTax = (($totalbill) * $datam2[0]['basic_tax'])/100;
            $KrishiKalyanTax    =   (($totalbill) * $datam2[0]['krishikalyan_tax'])/100;
            $SwachhBharatTax    =   (($totalbill) * $datam2[0]['swachhbharat_tax'])/100;
            $totalbill          =   $totalbill + $BasicTax + $KrishiKalyanTax + $SwachhBharatTax; 
            $totalbill	=	round($totalbill);
            //////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN END HERE ///////

            $Min_Pkg_Hrs=$datam2['Min_Pkg_Hrs'];
            $Min_Pkg_KM=$datam2['Min_Pkg_KM'];
            $per_km_charge=$datam2["per_km_charge"];
            $min_distance=$datam2["Min_Distance"];
            $wait_charge=$datam2["WaitingCharge_per_minute"];
            $waiting_min=$datam2["Waitning_minutes"];

            $basic_tax			=	$datam2['basic_tax'];
            $basic_tax_type		=	$datam2['basic_tax_type'];
            $basic_tax_price		=	round($BasicTax);
            $krishikalyan_tax           =   $datam2['krishikalyan_tax'];
            $swachhbharat_tax           =   $datam2['swachhbharat_tax'];
            $krishi_kalyan_tax_price    =	round($KrishiKalyanTax);
            $swachh_bharat_tax_price    =	round($SwachhBharatTax);

            $rounding			=	$datam2['rounding'];
            $level			=	$datam2['level'];
            $direction			=	$datam2['direction'];
            $nightcharge_unit		=	$datam2['nightCharge_unit'];
            $nightcharge		=	$datam2['NightCharges'];
            $nightcharge_price		=	round($NightCharges);
            $night_rate_begins		=	$datam2['night_rate_begins'];
            $night_rate_ends		=	$datam2['night_rate_ends'];
            $premiums			=	$datam2['premiums'];
            $premiums_unit		=	$datam2['premiums_unit'];
            $extraPrice			=	round($extraPrice);
            $peakTimePrice		=	round($PeakFare['peakcharge']);
            $peaktimeFrom		=	$PeakFare['peaktimeFrom'];
            $peaktimeTo			=	$PeakFare['peaktimeTo'];
            $peaktimepercentage		=	$PeakFare['peakpercentage'];
            $extras			=	str_replace(array('[',']'),'',$datam2['extras']);
            
            $faireDetails = array();
            $faireDetails['minimum_charge'] = $datam2['minimum_charge'];
            $faireDetails['total_bill'] = $totalbill;
            $faireDetails['total_tax'] =  round($BasicTax + $KrishiKalyanTax + $SwachhBharatTax);
            $faireDetails['night_charge'] = $NightCharges;
            $faireDetails['per_km_charge'] = $datam2['per_km_charge'];
            $faireDetails['extra_charge'] = $extraPrice;
            
            $faireDetails['basic_tax'] = $datam2[0]['basic_tax'];
            $faireDetails['krishikalyan_tax'] = $datam2[0]['krishikalyan_tax'];
            $faireDetails['swachhbharat_tax'] = $datam2[0]['swachhbharat_tax'];            
                       
            return $faireDetails;
            
        }           
        
    }
    
    public function pointBooking($post, $loginId, $booking_type_id)
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        
        //$is_pickup = mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointPickuparea'"));
        $select = $sql->select();
        $select->from('tbl_locations')
               ->columns(array('*'));
        $select->where(array('area'=> $post['point_pickup_area']));                 
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $is_pickup = $result->toArray();
        
        //$is_drop=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointDroparea'"));
        
        $select = $sql->select();
        $select->from('tbl_locations')
               ->columns(array('*'));
        $select->where(array('area'=> $post['point_drop_area']));                 
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $is_drop = $result->toArray();
        
        if(count($is_pickup)==0)
        {
            $find_pickup = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($post['point_pickup_area']));
            file_put_contents("json.txt",$find_pickup);
            $enc = json_decode($find_pickup);
            if($enc->status == 'OK')
            {
                $lat = $enc->results[0]->geometry->location->lat;
                $long = $enc->results[0]->geometry->location->lng;
		$area="";
                foreach($enc->results[0]->address_components as $v)
                {
                    if($v->types[0]=="locality")
                    {
                        $area = $v->long_name;
                    }
                    if($v->types[0]=="administrative_area_level_2")
                    {
                        $zone = $v->long_name;
                    }
                    if($v->types[0]=="country")
                    {
                        $country = $v->long_name;
                    }
                    if($v->types[0]=="administrative_area_level_1")						
                    {
                        $state = $v->long_name;
                    }
                }
                                               
                $insertPickupData = array(
                    'area'=> $post['point_pickup_area'],
                    'city' => $area,
                    'lat' => $lat,
                    'lon' => $long,
                    'zone' => $zone,
                    'country' => $country,
                    'state' => $state                    
                    
                );
                $insert = $sql->insert('tbl_locations');
                $insert->values($insertPickupData);
                $statement = $sql->getSqlStringForSqlObject($insert);
                $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            }else{
                return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Area');
            }
        }
        
        if(count($is_drop)==0)
        {
            $find_pickup = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($post['point_drop_area']));
            $enc2 = json_decode($find_pickup);
            if($enc2->status == 'OK')
            {
                $lat = $enc2->results[0]->geometry->location->lat;
		$long = $enc2->results[0]->geometry->location->lng;
		$destiny[0] = $lat;
		$destiny[1] = $long;
		$area="";
                foreach($enc2->results[0]->address_components as $v)
                {
                    if($v->types[0]=="locality")
                    {
                        $area = $v->long_name;
                    }
                    if($v->types[0]=="administrative_area_level_2")
                    {
                        $zone = $v->long_name;
                    }
                    if($v->types[0]=="country")
                    {
                        $country = $v->long_name;
                    }
                    if($v->types[0]=="administrative_area_level_1")						
                    {
                        $state=$v->long_name;
                    }
                }
                
                $insertDropData = array(
                    'area'=> $post['point_drop_area'],
                    'city' => $area,
                    'lat' => $lat,
                    'lon' => $long,
                    'zone' => $zone,
                    'country' => $country,
                    'state' => $state                    
                    
                );
                $insert = $sql->insert('tbl_locations');
                $insert->values($insertDropData);
                $statement = $sql->getSqlStringForSqlObject($insert);
                $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            }else{
                return array('Status'=>'false', 'msg'=>'Please enter valid Drop Area');
            }
        }
        
        
        if(!empty($loginId))
        {
           $result  = $this->checkLoginUser($post['point_email_id'], $post['point_mobile_no']);
           
           if(count($result)>0 && $result['0']['user_type']==1)
           {
               $client_id = $loginId;       
               
           }else if(count($result)>0 && $result['0']['user_type']==2){
               
               $client_id = $result['0']['id'];  
               
               
           }else{
               
               /**
                * This is user but booking for some one
                *  insert new user with unregister user_type and user id parent_id
                */
                             
               $insertUserData = array(
                'user_type' => 2,
                'login_name' => $post['point_email_id'],
                'user_no' => $post['point_mobile_no'],
                'parent_id' => $loginId,
                   
                       );
                $insert = $sql->insert('tbl_user');
                $insert->values($insertUserData);
                $statement = $sql->getSqlStringForSqlObject($insert);
                $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
                if($adapter->getDriver()->getLastGeneratedValue() > 0)
                {
                    if(isset($post['point_gst']) && $post['point_gst']=='on')
                    {
                        $gst = 'Yes';
                    }else{
                        $gst = 'No';
                    }
                    
            
                    $client_id = $uid = $adapter->getDriver()->getLastGeneratedValue();

                    $insertUserInfo = array(
                        'uid' => $uid,
                        'first_name' => $post['point_fname'],
                        'last_name' => $post['point_lname'],
                        'email' => $post['point_email_id'],
                        'mobile_no'=> $post['point_mobile_no'],
                        'alternate_contact_no' => $post['point_alt_mobile_no'],
                        'gst' => $gst,
                        'gst_registration_number' => $post['point_gst_reg_no'],
                        'gst_registered_company_name'=> $post['point_gst_reg_company_name'],
                        'nationality'=> $post['point_nationality'],
                        'gender' => $post['point_gender']

                    );           

                    $insert = $sql->insert('tbl_user_info');
                    $insert->values($insertUserInfo);
                    $statement = $sql->getSqlStringForSqlObject($insert);
                    $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
                }
           }
        } else{
            /**
             * This is guest user
             */
            $result  = $this->checkLoginUser($post['point_email_id'], $post['point_mobile_no']);
           
           if(count($result)>0)
           {
               
              $loginId = $client_id = $result[0]['id'];
               
           }else{               
                                            
               $insertUserData = array(
                'user_type' => 2,
                'login_name' => $post['point_email_id'],
                'user_no' => $post['point_mobile_no'],
                                   
                       );
                $insert = $sql->insert('tbl_user');
                $insert->values($insertUserData);
                $statement = $sql->getSqlStringForSqlObject($insert);
                $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
                if($adapter->getDriver()->getLastGeneratedValue() > 0)
                {
                    if(isset($post['point_gst']) && $post['point_gst']=='on')
                    {
                        $gst = 'Yes';
                    }else{
                        $gst = 'No';
                    }
                    
            
                   $client_id = $uid = $adapter->getDriver()->getLastGeneratedValue();

                    $insertUserInfo = array(
                        'uid' => $uid,
                        'first_name' => $post['point_fname'],
                        'last_name' => $post['point_lname'],
                        'email' => $post['point_email_id'],
                        'mobile_no'=> $post['point_mobile_no'],
                        'alternate_contact_no' => $post['point_alt_mobile_no'],
                        'gst' => $gst,
                        'gst_registration_number' => $post['point_gst_reg_no'],
                        'gst_registered_company_name'=> $post['point_gst_reg_company_name'],
                        'nationality'=> $post['point_nationality'],
                        'gender' => $post['point_gender']

                    );           

                    $insert = $sql->insert('tbl_user_info');
                    $insert->values($insertUserInfo);
                    $statement = $sql->getSqlStringForSqlObject($insert);
                    $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
                }
           }            
            
        }        
        
        //$fullName = $post['point_fname'] .' '. $post['point_lname'];
        $pickupTime = $post['point_pickup_time'];
        $pickupDate = $post['point_pickup_date'];
        //$emailId = $post['point_email_id'];
        //$mobileNo = $post['point_mobile_no'];//'9654148220';
        $city_id = $post['point_cab_in'];
        $cab_type = $post['cab_type_id'];
        $distance = $post['distance'];
        $resultCity = $this->getCityNameByCityId($city_id);
        $cityName = $resultCity[0]['city_name'];
                
        $insertData  = array(
			'device_type'=>'WEB',
			'booking_type'=> $booking_type_id,
			'cab_in' => $cityName,
			'car_type' => $post['cab_type_id'],
			'user_id' => $loginId,
			'user_agent'=> $_SERVER['HTTP_USER_AGENT'],
			'client_id'=> $client_id,
			'no_of_adults' => $post['point_adults'],
			'no_of_childs' => $post['point_childs'],
			'no_of_luggages' => $post['point_luggages'],
			'pickup_area' => $post['point_pickup_area'],
			'pickup_location' => $post['point_pickup_area'],
			'pickup_landmark'=> $post['point_pickup_address'],
			'drop_area' => $post['point_drop_area'],
			'drop_address' => $post['point_drop_area'],
			'destination_address'=> $post['point_drop_area'],
			'drop_location'=> $post['point_drop_area'],
			'pickup_state'=> $cityName,
			'pickup_address' => $post['point_pickup_address'],
			'pickup_date' => $pickupDate,
			'pickup_time' => $pickupTime,
			'booking_date' => '',
			'estimated_distance'=> $post['distance'],
			'pickup_latitude'=>'', 		
			'pickup_longtitude'=>'',
			'destination_latitude'=>'', 		
			'destination_longtitude'=>'',
			'pickup_city' => $cityName,
			'destination_city' => $cityName,
			'partner'=>1,
			'status'=>1,
			'peak_time'=>false
		);
	$insert = $sql->insert('tbl_cab_booking');
        
        $insert->values($insertData);
        $statement = $sql->getSqlStringForSqlObject($insert);
        $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
                      
        
        if($adapter->getDriver()->getLastGeneratedValue() > 0)
        {            
            $booking_id = $adapter->getDriver()->getLastGeneratedValue();
            if($DeviceType=="ANDROID")
            {
                $booking_ref = $this->newCheckWebCallAnd($booking_id,'HA');
            }else{
                $booking_ref = $this->newCheckWebCallAnd($booking_id,'HW');
            }
            
            $datam2 = $this->fetchPointToPointData($city_id, $booking_type_id, $cab_type, $distance);
            
            if($datam2!='')
            {
                $totalbill = $datam2['totalbill'];
                $estimated_price_before_markup  =   $datam2['minimum_charge'];
                if($point_MinimumBookingAmount!="")
                {
                    if($totalbill>$point_MinimumBookingAmount)
                    {
                        if($point_DiscountType=='RS')
                        {
                            $coupon_amt = $totalbill-$point_coupanDisount;
                        }
                        else if($point_DiscountType=='%')
                        {
                            $coupon_amt = $totalbill-($totalbill*$point_coupanDisount/100);
                        }
                    }
                }else{
                    $totalbill = $totalbill;
                }
                
                $PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
                $totalbill	=	$totalbill + $PeakFare['peakcharge'];
                $NightCharges = $this->calculateNigthCharges($pickupTime,$datam2[0]['night_rate_begins'],$datam2[0]['night_rate_ends'],$datam2[0]['night_charge_unit'],$datam2[0]['night_charges'],$totalbill);

                $totalbill	=	$totalbill + $NightCharges;
                $extraPrice	= $this->calculateExtraCharges($totalbill,$datam2[0]['extras']);

                $totalbill	=	$totalbill + $extraPrice;

                $BasicTax = (($totalbill) * $datam2[0]['basic_tax'])/100;
                $KrishiKalyanTax    =   (($totalbill) * $datam2[0]['krishikalyan_tax'])/100;
                $SwachhBharatTax    =   (($totalbill) * $datam2[0]['swachhbharat_tax'])/100;
                $totalbill          =   $totalbill + $BasicTax + $KrishiKalyanTax + $SwachhBharatTax; 
                $totalbill	=	round($totalbill);
                //////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN END HERE ///////

                $Min_Pkg_Hrs=$datam2['Min_Pkg_Hrs'];
                $Min_Pkg_KM=$datam2['Min_Pkg_KM'];
                $per_km_charge = $datam2["per_km_charge"];
                $min_distance = $datam2["min_distance"];
                $wait_charge = $datam2["waiting_charge_per_minute"];
                $waiting_min = $datam2["waiting_minutes"];

                $basic_tax			=	$datam2['basic_tax'];
                $basic_tax_type		=	$datam2['basic_tax_type'];
                $basic_tax_price		=	round($BasicTax);
                $krishikalyan_tax           =   $datam2['krishikalyan_tax'];
                $swachhbharat_tax           =   $datam2['swachhbharat_tax'];
                $krishi_kalyan_tax_price    =	round($KrishiKalyanTax);
                $swachh_bharat_tax_price    =	round($SwachhBharatTax);

                $rounding			=	$datam2['rounding'];
                $level			=	$datam2['level'];
                $direction			=	$datam2['direction'];
                $nightcharge_unit		=	$datam2['night_charge_unit'];
                $nightcharge		=	$datam2['night_charges'];
                $nightcharge_price		=	round($NightCharges);
                $night_rate_begins		=	$datam2['night_rate_begins'];
                $night_rate_ends		=	$datam2['night_rate_ends'];
                $premiums			=	$datam2['premiums'];
                $premiums_unit		=	$datam2['premiums_unit'];
                $extraPrice			=	round($extraPrice);
                $peakTimePrice		=	round($PeakFare['peakcharge']);
                $peaktimeFrom		=	$PeakFare['peaktimeFrom'];
                $peaktimeTo			=	$PeakFare['peaktimeTo'];
                $peaktimepercentage		=	$PeakFare['peakpercentage'];
                $extras			=	str_replace(array('[',']'),'',$datam2['extras']);
            }
            /*
             * Entry data in coupon master
             * 
             */
             //$com_query = "INSERT into tblcouponmaster (CouponID,userId,DeviceType) values('$point_coupan_id','$user_id','$DeviceType')"; 
                      
                        
            $PromotionName = $post['applied_code'];
            $point_coupan_id = $post['point_coupon_id']?$post['point_coupon_id']:'';
            $CouponName = $post['applied_code'];
            
            if($post['success_coupon']==1)
            {                
                $insertData = array(
                    'coupon_id'=> $point_coupan_id,
                    'user_id'=> $loginId,
                    'device_type'=> 'Web'
                );
                $insert = $sql->insert('tbl_coupon_master');
                $insert->values($insertData);
                $statement = $sql->getSqlStringForSqlObject($insert);
                $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
                $coupanLastId = $adapter->getDriver()->getLastGeneratedValue();
            }else{
                $coupanLastId = '';
            
            }
            
            
            $setValue = array(
                'estimated_price'=> $post['total_charge'],
                'approx_distance_charge'=> $per_km_charge,
                'approx_after_km'=> $min_distance,
                'approx_waiting_charge'=> $wait_charge,
                'appox_waiting_minute'=> $waiting_min,
                'min_distance'=> $min_distance,
                'promotional_code'=> $CouponName,
                'promotional_name'=> $PromotionName,
                'no_of_taxies'=> $post['point_cars'],
                'Package_State'=> $stateName,
                'coupan_id'=> $coupanLastId,
                'basic_tax' => $basic_tax,
                'basic_tax_type'=> $basic_tax_type,
                'basic_tax_price' => $basic_tax_price,
                'krishikalyan_tax' => $krishikalyan_tax,
                'swachhbharat_tax' =>$swachhbharat_tax,
                'krishikalyan_tax_price' => $krishi_kalyan_tax_price,
                'swachhbharat_tax_price' => $swachh_bharat_tax_price,
                'rounding' => $rounding,
                'level' => $level,
                'direction' => $direction,
                'night_charge_unit' => $nightcharge_unit,
                'night_charge' => $nightcharge,
                'night_charge_price' => $nightcharge_price,
                'night_rate_begins' => $night_rate_begins,
                'night_rate_ends' => $night_rate_ends,
                'premiums' => $premiums,
                'premiums_unit' => $premiums_unit,
                'extras' => $extras,
                'extra_price' => $extraPrice,
                'peak_time_Price' => $peakTimePrice,
                'peak_time_from' => $peaktimeFrom,
                'peak_time_to' => $peaktimeTo,
                'peak_time_percentage' => $peaktimepercentage,
                'coupon_amt' => $coupon_amt,
                'markup_type' => $markup_type,
                'markup_value' => $markup_value,
                'markup_price' => $markup_price,
                'estimated_price_before_markup' => $estimated_price_before_markup
                    );
            $update = $sql->update();
            $update->table('tbl_cab_booking');
            $update->set($setValue);
            $update->where(array('id' => $booking_id));
            $statement  = $sql->prepareStatementForSqlObject($update);            
            $result = $statement->execute(); 
            $this->send_sms_new($booking_id);
                        
            //$pickuplatlat = $origin[0];
            //$pickuplatlng = $origin[1];
            //$this->LogStackTrackerData($booking_id,$pickuplatlat,$pickuplatlng);
            //$this->send_sms_new($booking_id);
            //$retrurn =  array("Status" => "Success","per_km_charge"=>$per_km_charge, "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate,"succMess"=>"Your booking has been confirmed.","couponMsg"=>$couponMsg);
        }
        return $result;
    }
    
    public function fetchPointToPointData($city_id, $booking_type_id, $cab_type,$distance)
    {
        $status = 1;
        $ignore_hrs = 0;
        $ignore_km = 0;
        $minimumCharge = 0;
        
        $val = $this->fetchCalculationType($city_id, $booking_type_id, $cab_type,$distance,$minimumCharge,$ignore_hrs,$ignore_km,$status,$markup_type,$markup_value);
        
        $datam1 = $val;
        if($totalbill>$datam1['totalbill'])
        {
            $datam1['totalbill']=$totalbill;	
        }else{
            $datam1['totalbill']=$datam1['totalbill'];	
        }

        if($datam1['Min_Pkg_KM']!=""){
            $Min_Pkg_KM=$datam1['Min_Pkg_KM'];
        }else{
            $Min_Pkg_KM=$Min_Pkg_KM;
        }

        if($datam1['Min_Pkg_Hrs']!=""){
            $Min_Pkg_Hrs=$datam1['Min_Pkg_Hrs'];
        }else{
            $Min_Pkg_Hrs=$Min_Pkg_KM;
        }

        $datam1['Min_Pkg_KM'] = $Min_Pkg_KM;
        $datam1['Min_Pkg_Hrs'] = $Min_Pkg_Hrs;
        return $datam1;

    }
    
    public function calculatePeakTimeCharges($totalbill,$pickupTime)
    {
		
        $Fare = 0;
                
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('pt'=>'tbl_peak_time'))
               ->columns(array('*'));
                
        $select->where->lessThanOrEqualTo('time_from', $pickupTime);
        $select->where->greaterThanOrEqualTo('time_to', $pickupTime);
        
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $vaLue = $result->toArray();
                        
        $PeakChargPercent = $vaLue[0]["peak_charges"];
        $Fare = ($totalbill * $PeakChargPercent)/100;
        
        $PeakFare = array();
        $PeakFare['peakcharge'] = $Fare;
        $PeakFare['peaktimeFrom'] = $vaLue[0]["time_from"];
        $PeakFare['peaktimeTo'] = $vaLue[0]["time_to"];
        $PeakFare['peakpercentage'] = $vaLue[0]["peak_charges"];
        return $PeakFare;            
        
    }
    
    public function calculateNigthCharges($pickupTime,$nightRateBegins,$nigthRateEnds,$nightChargeUnit,$NightCharges,$totalbill)
    {
                
        if(strtotime($pickupTime) <= strtotime($nightRateBegins) && strtotime($pickupTime) < strtotime($nigthRateEnds))
        {            
            $nightRateEnds = strtotime($nigthRateEnds);
            if(strtotime($pickupTime)< $nightRateEnds)
            {                
                if($nightChargeUnit == 'Rs')
                {                    
                    $Night_Charges = $NightCharges;
                }else{
                    $Night_Charges = ($totalbill * $NightCharges)/100;
                }
            }
        }
        elseif(strtotime($pickupTime) >= strtotime($nightRateBegins) && strtotime($pickupTime) > strtotime($nigthRateEnds))
        {
            $nightRateEnds = strtotime($nigthRateEnds)+60*60*24;
            if($nightRateEnds>strtotime($pickupTime))
            {
                if($nightChargeUnit == 'Rs')
                {
                    $Night_Charges = $NightCharges;
                }else{
                    $Night_Charges = ($totalbill * $NightCharges)/100;
                }
            }
        }
        
        if($Night_Charges=="")
        {
            $Night_Charges = 0;
        }
        return $Night_Charges;
    }
    
    public function fetchCalculationType($city_id, $booking_type_id, $cab_type,$distance,$minimumCharge,$ignore_hrs,$ignore_km,$status,$markupType,$markupValue)
    {
        $datam1 = array();
        $data = $this->fetchBookingBill($city_id, $booking_type_id, $cab_type);
        $datam1 = $data;
        
                			
	/***********************************Calculate Fare*********/
	
	$adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('mp'=>'tbl_master_package'))
               ->columns(array('*'));
        $select->where(array('state_id'=>$city_id));
        $select->where(array('package_id'=>$booking_type_id));
        $statement = $sql->getSqlStringForSqlObject($select);
        
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $subpackage = $result->toArray(); 
        
	$permntavr = 40/60;
		
		if($status==1){
                    
			if($subpackage[0]['sub_package_id'] == 1){
				$ignore_hrs = 0;
				$ignore_km = $data[0]['min_distance'];
				$minimumCharge = $data[0]["minimum_charge"];
                                
			}elseif($subpackage[0]['sub_package_id'] == 2){
				$ignore_hrs = $data[0]["ignore_first_hrs"]; 
				$ignore_km = 0;
				$minimumCharge = $data[0]["minimum_hourly_charge"];
			}elseif($subpackage[0]['sub_package_id'] == 3){
				$ignore_hrs = $data[0]['ignore_first_hrs_dh'];
				$ignore_km = $data[0]['minimum_distance_dh'];
				$minimumCharge = $data[0]["minimum_fare_dh"];
			}elseif($subpackage[0]['sub_package_id'] == 4){
				$ignore_hrs = 0;
				$ignore_km = $data[0]['minimum_distance_dw'];
				$minimumCharge = $data[0]["minimum_fare_dw"];
			}
		}
		
	if($booking_type_id=="101"){
		if($ignore_km==0){
		$distance = $ignore_hrs.' hrs';
		}else{
		$distance = $ignore_km;
		}	
		$travel_dis = $ignore_km;
		$travel_hrs = $ignore_hrs;
		
		if (strpos($distance,'hrs') !== false) {
		$distance = explode(" ",$distance);
		
		$totalmint = $distance[0]*60;
		}else{		
		$totalmint = round($distance/$permntavr);
		}
	}elseif($booking_type_id=="102"){
		if($distance==0){
		$distance = $ignore_km;
		}else{
		$distance = $distance;
		}
	}	
		
        
        if($subpackage[0]['sub_package_id'] == 1)
        {
            if($distance > $ignore_km)
            {
                $ExtraKM = $distance - $ignore_km;
                if($markupType=='%')
                {
                    $data[0]["per_km_charge"]  =   $data[0]["per_km_charge"]+round((($data[0]["per_km_charge"]*$markupValue)/100));
                    $minimumCharge          =   $minimumCharge+round((($minimumCharge*$markupValue)/100));
                }
                elseif($markupType=='Rs')
                {
                    $minimumCharge = $minimumCharge+$markupValue;
                }
                $ExtraFare = $ExtraKM*$data[0]["per_km_charge"];
                $EstimatedPrice = $ExtraFare + $minimumCharge;
                
            }else{
                if($markupType=='Rs')
                {
                    $minimumCharge = $minimumCharge+$markupValue;
                }
                $EstimatedPrice = $minimumCharge;
            }
            
            $datam1['per_km_charge'] = $data[0]["per_km_charge"];
            $datam1['min_distance'] = $ignore_km;
            $datam1['minimum_charge'] = $minimumCharge;
            
        }
        elseif($subpackage[0]['sub_package_id'] == 2)
        {
            $ignore_first_hours = $ignore_hrs*60;
            if($totalmint > $ignore_first_hours)
            {
                $rest_min = $totalmint-$ignore_first_hours;
                if($markupType=='%')
                {
                    $data[0]["trip_charge_per_minute"]  =   $data[0]["trip_charge_per_minute"]+round((($data[0]["trip_charge_per_minute"]*$markupValue)/100));
                    $minimumCharge          =   $minimumCharge+round((($minimumCharge*$markupValue)/100));
                }elseif($markupType=='Rs')
                {
                    $minimumCharge = $minimumCharge+$markupValue;
                }
                $ExtraFare = ($rest_min/60)*$data[0]["trip_charge_per_minute"];
                $EstimatedPrice = $ExtraFare + $minimumCharge;
            }else{
                if($markupType=='Rs')
                {
                    $minimumCharge = $minimumCharge+$markupValue;
                }
                $EstimatedPrice = $minimumCharge;
            }
            
	//// In Case per Hourly Charge 120 Rs and If car is running 40 Km Per hrs then per km charge is 120/40 is 3 Rs per Km Charge
	$datam1['per_km_charge'] = $data[0]["trip_charge_per_minute"];
	$datam1['min_distance'] = $ignore_hrs*40;
	$datam1['minimum_charge'] = $minimumCharge;
	
	}
			
	elseif($subpackage[0]['sub_package_id'] == 3){
	$totalmint = $travel_hrs;
	if($distance < $ignore_km){
	$distanceRate=0;
	}
	else{
            $remain_km = $distance - $ignore_km;
            if($markupType=='%'){   
            $data[0]["rate_per_km_dh"]  =   $data[0]["rate_per_km_dh"]+round((($data[0]["rate_per_km_dh"]*$markupValue)/100));
            }
            $distanceRate = $remain_km*$data[0]["rate_per_km_dh"];
	}
	if($travel_hrs > $ignore_hrs){
		$hourlyRate=0;
	}else{
		$hourlyRate = $travel_hrs-$ignore_hrs;
		$rate_per_min = $data[0]["rate_per_hour_dh"]/60;
                if($markupType=='%'){
                $rate_per_min  =   $rate_per_min+round((($rate_per_min*$markupValue)/100));
                }
		$hourlyRate = $hourlyRate*$rate_per_min;
	}
        if($markupType=='%'){
        $minimumCharge          =   $minimumCharge+round((($minimumCharge*$markupValue)/100)); 
        }elseif($markupType=='Rs'){
        $minimumCharge          =   $minimumCharge+$markupValue;
        } 
	$EstimatedPrice = $distanceRate+$hourlyRate+$minimumCharge;
	$datam1['min_distance'] = $ignore_km;
	$datam1['minimum_charge'] = $minimumCharge;
	$datam1['per_km_charge'] = $data[0]["rate_per_km_dh"];
	$datam1['per_hr_charge'] = $data[0]["rate_per_hour_dh"];	
	}		
	
	elseif($subpackage[0]['sub_package_id'] == 4){		
	if($distance > $ignore_km){
	$ExtraKM = $distance - $ignore_km;
        
        if($markupType=='%'){
        $data["rate_per_km_dw"]  =   $data[0]["rate_per_km_dw"]+round((($data[0]["rate_per_km_dw"]*$markupValue)/100));
        $minimumCharge          =   $minimumCharge+round((($minimumCharge*$markupValue)/100));
        }elseif($markupType=='Rs'){
        $minimumCharge          =   $minimumCharge+$markupValue;
        }
        
	$ExtraFare = $ExtraKM*$data[0]["rate_per_km_dw"];
	$EstimatedPrice = $ExtraFare + $minimumCharge;
	}
	else{
        
        if($markupType=='Rs'){
        $minimumCharge          =   $minimumCharge+$markupValue;
        }
        
	$EstimatedPrice = $minimumCharge;								
	}
	$datam1['Per_Km_Charge'] = $data[0]["rate_per_km_dw"];
	$datam1['Min_Distance'] = $ignore_km;
	$datam1['MinimumCharge'] = $minimumCharge;	
	}
		
	////////////// Driver Share and company share calculation ////
	
	/***********************************************************************/
	
	$datam1['totalbill'] = $EstimatedPrice;
	$datam1['Min_Pkg_Hrs'] = $ignore_hrs;
	$datam1['Min_Pkg_KM'] = $ignore_km;
        return $datam1;	
    }
    
    public function fetchBookingBill($city_id, $booking_type_id, $cab_type)
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('bf'=>'tbl_base_fare'))
               ->columns(array('*'));
        $select->where(array('city_id'=>$city_id));
        $select->where(array('booking_type_id'=>$booking_type_id));
        $select->where(array('cab_type'=>$cab_type));
        $statement = $sql->getSqlStringForSqlObject($select);
        
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        return $result->toArray(); 
    }
    
    public function calculateExtraCharges($totalbill,$extras)
    {
        $extrasArr = json_decode($extras);
        
	$totalbillValue1 = 0;
	for($i=0;$i<count($extrasArr);$i++)
        {
            $totalbillValue = 0;
            $extrasArr_key = explode("_",$extrasArr[$i]);
            
            if($extrasArr_key[2]=="Rs")
            {
                $totalbillValue = $extrasArr_key[1];                
            }
            elseif($extrasArr_key[2]=="%")
            {
                $totalbillValue = ($totalbill * $extrasArr_key[1])/100;
            }
            $totalbillValue1 = $totalbillValue1+$totalbillValue;
        }
        return $totalbillValue1;
		
    }
    
    public function checkCoupancodeExistence($coupon_code,$booking_type)
    {
        $promoDetails = array();
        //date_default_timezone_set("Asia/Kolkata");
	$date = date('Y-m-d');
	$time = date('H:i');
	$day = date('l', strtotime($date));
        $coupan_code = $coupon_code;
	$bookingType_id = $booking_type;
	$adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('pr'=>'tbl_promotion'))
               ->columns(array('*','cou_id'=> new Expression('pr.id')));
        $select->join(array('prm'=>'tbl_promotion_master'), 'pr.coupon_type = prm.id', array('Promo_Name'=> new Expression('prm.promotion_name')), 'Inner');
        $select->where(array('pr.promotion_name'=>$coupan_code));
        $select->where(array('pr.booking_type_id'=>$bookingType_id));       
        $select->where->lessThanOrEqualTo('valid_date_from', $date);
        $select->where->greaterThanOrEqualTo('valid_date_to', $date);                
        $select->where->lessThanOrEqualTo('valid_time_from', $time);
        $select->where->greaterThanOrEqualTo('valid_time_to', $time);          
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $info = $result->toArray();
        
      if(count($info)>0)
      {
            $WeekDays = $info[0]['week_days'];
            $val = explode(',',$WeekDays);
            if (in_array($day, $val)) 
            {
                    $coupan_id = $info[0]['cou_id'];
                    $DiscountType = $info[0]['discount_type'];
                    $Discount = $info[0]['discount'];
                    $Promo_Name = $info[0]['promotion_name'];
                    $MinimumBookingAmount = $info[0]['minimum_booking_amount'];
                    $status="true";
            }
            else
              {
                    $status="false";
                    $DiscountType = "";
                    $Discount = "";
                    $Promo_Name="";
                    $coupan_id="";
                    $MinimumBookingAmount="";
              }
      }
      else
      {
              $status="false";
              $DiscountType = "";
              $Discount = "";
              $Promo_Name="";
              $coupan_id="";
              $MinimumBookingAmount="";
      }
      $promoDetails['status'] = $status;
      $promoDetails['discount'] = $Discount;        
      $promoDetails['promo_name'] = $Promo_Name;
      $promoDetails['coupan_id'] = $coupan_id; 
      $promoDetails['discount_type'] = $DiscountType;
      $promoDetails['minimum_booking_amount'] = $MinimumBookingAmount; 
      return $promoDetails;
    }
    
    public function checkCoupanCode($local_coupan_id,$localPhone,$localEmail)
    {
        $coupan_id = $local_coupan_id;
        $userno = $localPhone;
        $email = $localEmail;
           
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('u'=>'tbl_user'))
               ->columns(array('*'));
        $select->join(array('cm'=>'tbl_coupon_master'), 'u.id = cm.user_id', array('*'), 'Inner');
        $select->where(array('u.user_no'=>$userno));
        $select->where(array('u.login_name'=>$email));
        $select->where(array('cm.coupon_id'=>$coupan_id));
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $info = $result->toArray();
        
        if(count($info)>0)
        {
            //echo "this coupan is already used. please change the Coupan code";
            $status = "true";
            
        }else{
            $status = "false";
        }
        return $status;
    }
    
    public function fetchUserId($emailIds,$mobileNo,$userNames,$pointMobile_alt)
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('u'=>'tbl_user'))
               ->columns(array('*'));
        $select->where(array('login_name'=> $emailIds));
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $no_of_rows = $result->toArray();
        
        if(count($no_of_rows)==0)
        {                        
            $insert = $sql->insert('tbl_user');
            $newData = array('login_name'=> $emailIds,'user_no'=> $mobileNo);
            $insert->values($newData);
            $statement = $sql->getSqlStringForSqlObject($insert);
            $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);            
            
            $user_id = $sql->lastInsertValue;
                                   
            $insert = $sql->insert('tbl_user_info');
            $userInfoData = array('first_name'=> $userNames,'uid'=> $user_id,
                'mobile_no'=> $mobileNo, 'email'=>$emailIds,'alternate_contact_no'=>$pointMobile_alt);
            $insert->values($userInfoData);
            $statement = $sql->getSqlStringForSqlObject($insert);
            $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            
        }else{
            $user_id = $no_of_rows[0]['id'];
        }
        return $user_id;
    }
    
    public function newCheckWebCallAnd($val,$initial)
    {
       $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter); 
        //// Code for year Starts Here ///
        $dateYear = date('y');
        $dateYear = 64+$dateYear;
        $dateYear = chr($dateYear);
        //// Code for year Ends Here ///

        //// Code for Month Starts Here ////////
        $dateMonth = date('m');
        $dateMonth = 64+$dateMonth;
        $dateMonth = chr($dateMonth);
        //// Code for Month Ends Here///////

        $final1 = str_pad($val,4,0,STR_PAD_LEFT);
        $datam1 = array();

        if($val>=10000)
        {            
            $divide = floor($val/10000);
            $next = $val-($divide*10000);
            $aa = 64+$divide;
            $neww = chr($aa);
            $final = str_pad($next,4,0,STR_PAD_LEFT);
            $id = $initial.''.$dateYear.''.$dateMonth.''.$neww.''.$final;
            $generated = $id;
                       
            $update = $sql->update();
            $update->table('tbl_cab_booking');
            $update->set( array('booking_reference'=> $id) );
            $update->where( array( 'id' => $val ) );
            $statement  = $sql->prepareStatementForSqlObject( $update );
            $results    = $statement->execute();            
            $status = "true";
        }else{
                        
            $id = $initial.''.$dateYear.''.$dateMonth.''.$final1;
            $generated = $id;
            $update = $sql->update();
            $update->table('tbl_cab_booking');
            $update->set( array('booking_reference'=> $id) );
            $update->where( array( 'id' => $val ) );
            $statement  = $sql->prepareStatementForSqlObject( $update );
            $results    = $statement->execute();            
            $status = "true";
        }

        $datam1['generated'] = $generated;
        return $datam1;
    }
    
    public function send_sms_new($booking=0)
    {
        $adapter = $this->tableGateway->getAdapter();
        $booking_id="";
	if($booking==0)
        {
            $booking_id = $_REQUEST['booking_id'];
        }else{
            $booking_id = $booking;
        }
          
        
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('t2'=>'tbl_cab_booking'))
               ->columns(array('*'));
        $select->join(array('t1'=>'tbl_base_fare'), 't2.booking_type = t1.booking_type_id AND t2.car_type=t1.cab_type', array('*'), 'Inner');
        $select->join(array('t4'=>'tbl_cities'), 't2.cab_in=t4.city_name AND t4.id = t1.city_id', array('*'), 'Inner');
        $select->join(array('t3'=>'tbl_master_package'), 't2.booking_type = t3.package_id', array('*'), 'Inner');
        $select->where(array('t2.id'=>$booking_id));       
        $statement = $sql->getSqlStringForSqlObject($select);      
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $fetch = $result->toArray();           
                
        $Sub_Package_Id = $fetch['0']['sub_package_id'];
        //$mobile = $fetch['0']['mobile_no'];
        //$email = $fetch['0']['email_id'];
        $booking_ref = $fetch['0']['booking_reference'];
        $client_id = $fetch['0']['client_id'];
        
        /** This one start to get user info */
        
        
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('uinfo'=>'tbl_user_info'))
               ->columns(array('first_name','last_name', 'email', 'mobile_no'));
        $select->where(array('uid'=>$client_id));       
        $statement = $sql->getSqlStringForSqlObject($select);      
        $rUser   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $fUser = $rUser->toArray();
        
        //$client = $fUser['0']['first_name'] .' '. $fUser['0']['last_name'];
        $client = ucfirst($fUser['0']['first_name']);
        $mobile = $fUser['0']['mobile_no'];
        $email = $fUser['0']['email'];
        
        /** This one end to get user info */
        
        
        //$client = $fetch['0']['user_name'];
        
        $bookingdate = $fetch['0']['booking_date'];
        $cabname = $fetch['0']['cab_name'];
        if($Sub_Package_Id==1)
        {
        $minimum_charge = $fetch['0']['minimum_charge'];
        $minimum_distance = $fetch['0']['min_distance'];
        $charge = $fetch['0']['per_km_charge'];
        $waiting_charge=0;
        }
        elseif($Sub_Package_Id==2)
        {
        $minimum_charge = $fetch['0']['minimum_hourly_charge'];
        $minimum_distance = $fetch['0']['ignore_first_hrs'];
        $charge = $fetch['0']['trip_charge_per_minute'];
        $waiting_charge = $fetch['0']['waiting_charge_per_minute'];
        }
        elseif($Sub_Package_Id==3)
        {
        $minimum_charge = $fetch['0']['minimum_fare_dh'];
        $minimum_distance = $fetch['0']['minimum_distance_dh'];
        $charge = $fetch['0']['rate_per_km_dh'];
        $waiting_charge = $fetch['0']['waiting_charge_per_minute'];
        }
        elseif($Sub_Package_Id==4)
        {
        $minimum_charge = $fetch['0']['minimum_fare_dw'];
        $minimum_distance = $fetch['0']['minimum_distance_dw'];
        $charge = $fetch['0']['rate_per_km_dw'];
        $waiting_charge = 0;
        }

        $distance = $fetch['0']['estimated_distance'];
        $pickup_time = $fetch['0']['pickup_date']." ".$fetch['0']['pickup_time'];
        $pickup_time = date('d/m/Y h:iA', strtotime(" +0 hours +0 minutes", strtotime($pickup_time)));
        $pick = $fetch['0']['pickup_location'];
        $uid = $fetch['0']['client_id'];
        
        if($Sub_Package_Id==2)
        {
            
            $select = $sql->select();
            $select->from(array('smst'=>'tbl_sms_template'))
               ->columns(array('*'));
            $select->where(array('msg_sku'=>'booking_hrs'));
            $statement = $sql->getSqlStringForSqlObject($select);
            $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            $msg_query = $result->toArray();
        }
        else
        {
            
            $select = $sql->select();
            $select->from(array('smst'=>'tbl_sms_template'))
               ->columns(array('*'));
            $select->where(array('msg_sku'=>'booking'));
            $statement = $sql->getSqlStringForSqlObject($select);
            $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
            $msg_query = $result->toArray();
        }
        $array = explode('<variable>',$msg_query['0']['message']);
        $array[0] = $array[0].$client;
        $array[1] = $array[1].$booking_ref;
        $array[2] = $array[2].$pickup_time;
        $array[3] = $array[3].$fetch['0']['estimated_price'];
        
        $text =  urlencode(implode("",$array));	
        file_put_contents("mssg.txt",$text);
        $url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";

        file_get_contents($url);
        //mysqli_query($this->con,"INSERT INTO `tblsmsstatus`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing hello42 cabs','$mobile','1')");
        
        $insert = $sql->insert('tbl_sms_status');
        $newData = array('uid'=> $uid,'mesg'=> 'Thankyou for choosing hello42 cabs', 'contact_no'=> $mobile, 'status'=> '1');
        $insert->values($newData);
        $statement = $sql->getSqlStringForSqlObject($insert);
        $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);

        $message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Hello42cab</title>
        <style>
        *{margin:0;
        padding:0;
        }
        </style>
        </head>

        <body style="font-family:Verdana, Geneva, sans-serif">
        <div align="center">
        <table cellpadding="0px" cellspacing="0px" style="border:#fab600 solid 30px" width="650px" >
        <tr>
        <td>
        <table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px">
        <tr><td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold" width="300px" valign="middle" align="left">Confirmed CRN '.$booking_ref.'</td>
        <td width="200px" valign="middle" align="right"><img src="images/hello-42-logo.png" width="100px" height="60px" /></td></tr>
        </table>
        <table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif"><p>Hi'.$client.'<br />Thank you for using Hello42 Cab! Your Cab booking is confirmed.<br /><br /><span style="font-size:12px">BOOKING DETAILS</span><br />........................................................................................................</p></td></tr>

        </table>
        <table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
        <tr><td style="padding:10px 0px 0px 80px">Booking time</td><td valign="top" style="padding:10px 0px 0px 50px">'.$bookingdate.'</td></tr>
        <tr><td style="padding:0px 0px 0px 80px">Pickup time</td><td valign="top" style="padding:0px 0px 0px 50px">'.$pickup_time.'</td></tr>
        <tr><td style="padding:0px 0px 0px 80px">Pickup address</td><td valign="top" style="padding:0px 0px 0px 50px">'.$pick.'</td></tr>
        <tr><td style="padding:0px 0px 10px 80px">Car type</td><td valign="top" style="padding:0px 0px 10px 50px">'.$cabname.'</td></tr></table>

        <table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif"><span style="font-size:12px">FARE DETAIL</span><br />........................................................................................................</td></tr>

        </table>

        <table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
        <tr><td style="padding:10px 0px 0px 80px">Minimum bill</td><td valign="top" style="padding:10px 0px 0px 50px">'.$fair.'</td></tr>
        <!--<tr><td style="padding:0px 0px 0px 80px">After 8 Km</td><td valign="top" style="padding:0px 0px 0px 50px">Rs 18 per Km</td></tr>
        <tr><td style="padding:0px 0px 10px 80px">After 10 minutes</td><td valign="top" style="padding:0px 0px 10px 50px">Rs 2 per minute</td></tr>--></table>

        <table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 10px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif; text-align:center; font-size:12px; font-weight:bold"><p>*Parking and toll charges extra. Waiting charges applicable for in-trip waiting time also</p></td></tr>

        </table>

        <table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 10px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif; text-align:center; font-size:12px; font-weight:bold"><p>Please refer your CRN for all communication about this booking.</p></td></tr>
        </table>

        <table cellpadding="0px" cellspacing="0px" width="650" style="background-color:#000; color:#FFF; font-size:12px; text-align:center; padding:15px 0px 15px 0px" >
        <tr>
        <td valign="middle"><p><img src="images/mobile-48.png" height="32px" width="32px" /><br />Go Mobile! <br />Book with one touch</p></td>
        <td valign="middle"><p><img src="images/1419443343_phone-32.png" /><br />Get in touch <br />Call on (011) 42424242</p></td>
        <td valign="middle"><p><img src="images/facebook.png" /><img src="images/twitter.png" /><br />Connect  <br />On Twitter/Facebook</p></td>
        <td valign="middle"><p><img src="images/hello-42-logo.png" height="40px" width="55px"/><br />Learn what\'s new  <br />And more on our Blog</p></td>
        </tr>
        </table>
        </td></tr>
        </table>
        </div>
        </body>
        </html>';
        //mysqli_query($this->con,"INSERT INTO `tblemailhostory`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing Hello42 cabs','$mobile','1')");
          
        /*$insert = $sql->insert('tbl_email_history');
        $data = array('uid'=> $uid,'mesg'=> 'Thankyou for choosing hello42 cabs', 'contact_no'=> $mobile, 'status'=> '1');
        $insert->values($data);
        $statement = $sql->getSqlStringForSqlObject($insert);
        $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);*/
        
        //$this->mailing_new($email,$message,"Congragtulation","Hello42@cab.com");
        return array('status'=>true);
    }
    
    public function getCityNameByCityId($cityId)
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('city'=>'tbl_cities'))
           ->columns(array('city_name'));
        $select->where(array('id'=> $cityId));
        $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $data = $result->toArray(); 
        return $data;
        
    }
    
    public function checkLoginUser($emailId, $mobileNo)
    {
        $adapter = $this->tableGateway->getAdapter();
        $sql     = new Sql($adapter);
        $select = $sql->select();
        $select->from('tbl_user')
           ->columns(array('*'));
        $select->where(array('login_name'=> $emailId));
        $select->where(array('user_no'=> $mobileNo));
        $statement = $sql->getSqlStringForSqlObject($select);
        $result   = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $data = $result->toArray();
        return $data;
                     
        
    }
    
    public function checkCoupon($applyCoupn, $booking_type_id)
    {
        $couponDetails = array(); 
        if($applyCoupn!="")
        {
            $couponCodeData = $this->checkCoupancodeExistence($applyCoupn, $booking_type_id);
            
            if($couponCodeData['status']=="true")
            {
                $PromotionName = $couponCodeData['promo_name'];
                $point_coupan_id = $couponCodeData['coupan_id'];
                $point_DiscountType = $couponCodeData['discount_type'];
                $point_coupanDisount = $couponCodeData['discount'];
                $point_MinimumBookingAmount = $couponCodeData['minimum_booking_amount'];
                $CouponName = $applyCoupn;
                $couponMsg = "You have entered Valid Coupon Code.";
                
            }
            else{
                $PromotionName="";
                $point_coupan_id="";
                $point_DiscountType="";
                $point_coupanDisount="";
                $point_MinimumBookingAmount="";
                $CouponName="";
                $couponMsg = "You have entered In-Valid Coupon Code.";
            }
        }else{
            $PromotionName="";
            $point_coupan_id="";
            $point_DiscountType="";
            $point_coupanDisount="";
            $point_MinimumBookingAmount="";
            $CouponName="";
            $couponMsg="";
             
        }
        $couponDetails['PromotionName'] = $PromotionName;
        $couponDetails['point_coupan_id'] = $point_coupan_id;
        $couponDetails['point_DiscountType'] = $point_DiscountType;
        $couponDetails['point_coupanDisount'] = $point_coupanDisount;
        $couponDetails['point_MinimumBookingAmount'] = $point_MinimumBookingAmount;
        $couponDetails['CouponName'] = $CouponName;
        $couponDetails['couponMsg'] = $couponMsg;
        return $couponDetails;
        
    }
        
}