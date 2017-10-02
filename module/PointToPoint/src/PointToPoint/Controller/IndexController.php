<?php

namespace PointToPoint\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use PointToPoint\Model\PointToPoint;
use PointToPoint\Form\PointToPointForm;
use Zend\Session\Container;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();   
        
    }
    
    public function pointAction()
    {
	
        /*
         * $moduleType should be field name of tbl_cities.
         */
        $moduleType = 'point';
        $cities = $this->getServiceLocator()->get('CitiesTable');
        $quantity = $this->getServiceLocator()->get('QuantityTable');
        $locations = $this->getServiceLocator()->get('LocationsTable');
        $ncr = $cities->getCityNameDelhiNcr($moduleType);
        $ncrCityName = array();
        $ncrCityName[''] = 'Select City';
        foreach($ncr as $val){
            $ncrCityName[$val['id']] = $val['city_name'];
            
        }
        $qtyVal = $quantity->getQuantity($status=1);
        $qtyVals = array();
        $qtyChilds = array();
        $qtyLuggages = array();
        $qtyLuggages['0'] = 0;
        $qtyChilds['0'] = 0;
        foreach($qtyVal as $val){
            $qtyVals[$val['quantity_value']] = $qtyChilds[$val['quantity_value']] = $qtyLuggages[$val['quantity_value']] = $val['quantity_value'];
                   
        }
                                
        $form = new PointToPointForm();
        //$form->get('agreeterms')->setValue(1);
        $user_ip = $user_ip = $_SERVER[‘REMOTE_ADDR’];
        $get_details = get_meta_tags('http://www.geobytes.com/IPLocator.htm?GetLocation&template=php3.txt&IPAddress=' . $user_ip);
        //echo $get_details['country'];
        //echo $get_details['region'];
        //echo $get_details['city'];
        //$cityId = $cities->getCityIdByCityName($get_details['city'], $moduleType);
        
        $form->get('pointCabIn')->setAttribute('value', $cityId['0']['id']);
        
        $form->get('pointCabIn')->setAttribute('options' ,$ncrCityName);
        $form->get('pointAdults')->setAttribute('options' ,$qtyVals);
        $form->get('pointChilds')->setAttribute('options' ,$qtyChilds);
        $form->get('pointLuggages')->setAttribute('options' ,$qtyLuggages);
        $form->get('pointCars')->setAttribute('options' ,$qtyVals);
 
        // Getting a request object
        $request = $this->getRequest();
         
        // then request will be post
        if($request->isPost()){
            
            $post = $request->getPost()->toArray();
                        
            return $this->forward()->dispatch('PointToPoint/Controller/Index', [
                'action'     => 'car-list',
                'myObject'   => $post,
        ]);
             
        }         
    
        return new ViewModel(array('form'=>$form));
                
    }
    
    public function locationAction()
    {   
        $request = $this->getRequest();
	$postdata = $request->getPost();
                                
        $data = array();      
        $term =  $postdata['term'];
        $city =  $postdata['city'];
        $locations = $this->getServiceLocator()->get('LocationsTable');
                
        $row = $locations->location($city,$term);
        foreach($row as $val){
            $data[] = array(
                'id' => $val['id'],
                "label" => $val['area'],
                "value" => $val['lat'] . ',' . $val['lon'],
                'Country' => $val['country'],
                'State' => $val['state'],
                'City' => $val['city'],
                'Zone' => $val['zone']
            );            
        }
                            
        $view = new JsonModel($data);
        $view->setTerminal(true);
        return $view;
        
    }
    
    public function carListAction()
    {
        $booking_type_id = 102;// for Point to Point                      
        $request = $this->getRequest();
	$data = $request->getPost();
        //echo $this->params()->fromPost('data');
        $cities = $this->getServiceLocator()->get('CitiesTable');
        $quantity = $this->getServiceLocator()->get('QuantityTable');
        $locations = $this->getServiceLocator()->get('LocationsTable');
        $cabType = $this->getServiceLocator()->get('CabTypeTable');
        $baseFareTable = $this->getServiceLocator()->get('BaseFareTable');
        $ncr = $cities->getCityNameDelhiNcr($ncr=1);
        $ncrCityName = array();
        $ncrCityName[] = 'Select City';
        foreach($ncr as $val){
            $ncrCityName[$val['id']] = $val['city_name'];
            
        }
        $qtyVal = $quantity->getQuantity($status=1);
        $qtyVals = array();
        $qtyChilds = array();
        $qtyLuggages = array();
        $qtyLuggages['0'] = 0;
        $qtyChilds['0'] = 0;
        foreach($qtyVal as $val){
            $qtyVals[$val['quantity_value']] = $qtyChilds[$val['quantity_value']] = $qtyLuggages[$val['quantity_value']] = $val['quantity_value'];
                   
        }
                        
        $form = new PointToPointForm();
        $form->get('pointCabIn')->setAttribute('options' ,$ncrCityName);
        $form->get('pointAdults')->setAttribute('options' ,$qtyVals);
        $form->get('pointChilds')->setAttribute('options' ,$qtyChilds);
        $form->get('pointLuggages')->setAttribute('options' ,$qtyLuggages);
        $form->get('pointCars')->setAttribute('options' ,$qtyVals);
        
        $form->get('term')->setAttribute('value', $data['term']);
        $form->get('pointDropArea')->setAttribute('value', $data['pointDropArea']);
        $form->get('pointAddress')->setAttribute('value', $data['pointAddress']);
        
        $form->get('pointAdults')->setAttribute('value', $data['pointAdults']);
        $form->get('pointChilds')->setAttribute('value', $data['pointChilds']);
        $form->get('pointLuggages')->setAttribute('value', $data['pointLuggages']);
        $form->get('pointCars')->setAttribute('value', $data['pointCars']);
        $form->get('pointLaterDate')->setAttribute('value', $data['pointLaterDate']);
        
        $form->get('pointCabIn')->setAttribute('value', $data['pointCabIn']);
        
        
        if($data['pickup']=='Pick Now')
        {
          //$data['pointLaterDate'] = date("d/m/Y h:iA", strtotime("+30 minutes"));
            $data['pointLaterDate'] = date("Y-m-d h:iA", strtotime("+30 minutes"));
            
                  
        }        
        $cabType = $cabType->getCabType($data['pointCabIn'], $booking_type_id);
        
        $form->get('pointLaterDate')->setAttribute('value', $data['pointLaterDate']);
        
        return new ViewModel(array('form'=>$form,'data' => $data, 'cabType'=>$cabType,
            'baseFareTable'=>$baseFareTable));
    }
    
    public function ajaxPaymentAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost()->toArray();
        $viewModel = new ViewModel(array('data'=> $post));
        $viewModel->setTemplate('point-to-point/index/right-payment');
        $viewModel->setTerminal(true);
        return $viewModel;        
                
    }
    
    public function ajaxCouponAction()
    {
        $user_session = new Container('User');
        $emailIds = $user_session->username;
        $mobileNo = $user_session->mobileNo;
        $booking_type_id = 102;
        $request = $this->getRequest();
        $post = $request->getPost()->toArray();
        $totalbill = $post['total_charge'];
        $baseFareTable = $this->getServiceLocator()->get('BaseFareTable');  
        $result = $baseFareTable->checkCoupon($post['coupon_code'], $booking_type_id);
                
        if($result['point_coupan_id']!="")
        {
            $coupan_status = $baseFareTable->checkCoupanCode($result['point_coupan_id'],$mobileNo,$emailIds);
            if($coupan_status == "true")
            {
                
                $status = false;
                $coupon_amt = $totalbill;
                
            }else{
                
                
                if($result['point_MinimumBookingAmount']!="")
                {
                    $sCoupon = 0;   
                    if($totalbill>$result['point_MinimumBookingAmount'])
                    {
                                           
                        if($result['point_DiscountType']=='RS')
			{                                                        
                            $coupon_amt = $totalbill- $result['point_coupanDisount'];
                            
                        }
                        else if($result['point_DiscountType']=='%')
                        {
                            $coupon_amt = $totalbill-($totalbill*$result['point_coupanDisount']/100);
                        }
                        /**
                         * Entry coupon id in Coupon master table after Applied coupon 
                         */
                        $sCoupon = 1;
                                               
                        
                    }else{
                        $coupon_amt = $totalbill;
                        
                    }
                    
                }              
                $status = true;                              
                
            }
        }else{
            $coupon_amt = $totalbill;
            $coupanLastId ="";
        }
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('point-to-point/index/coupon');
        $viewModel->setTerminal(true);
        return $viewModel->setVariables(array('status'=>$status,'totalbill'=>round($coupon_amt),
            'sCoupon'=>$sCoupon,'appliedCode'=> $post['coupon_code'], 'point_coupon_id'=>$result['point_coupan_id']));
         
        
    }    
    public function ajaxPointBookCabAction()
    {        
        $user_session = new Container('User');
        $booking_type_id = 102;// for Point to Point
        $request = $this->getRequest();
        $post = $request->getPost()->toArray();
        $baseFareTable = $this->getServiceLocator()->get('BaseFareTable');               
        $baseFareTable->pointBooking($post, $user_session->id, $booking_type_id);
        $this->flashMessenger()->addMessage(array('success' => 'Custom success message to be here...'));
        return $this->redirect()->toRoute('default', array('controller'=>'index', 'action'=>'point'));
    }
    
    public function ajaxGetCurrentLocationAction()
    {
        $request = $this->getRequest();
	$postdata = $request->getPost();
          
        if(!empty($postdata['latitude']) && !empty($postdata['longitude']))
        {
            $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($postdata['latitude']).','.trim($postdata['longitude']).'&sensor=false';
            $json = file_get_contents($url);
            $data = json_decode($json);
            $status = $data->status;
            
            if($status=="OK")
            {                
                $fullAddress =  $data->results[0]->formatted_address;
            }else{
                $fullAddress = '';
            }
        }
        
        $viewModel = new ViewModel();
        $viewModel->setTemplate('point-to-point/index/address');
        $viewModel->setTerminal(true);
        return $viewModel->setVariables(array('fullAddress'=> $fullAddress));        
    }
}

