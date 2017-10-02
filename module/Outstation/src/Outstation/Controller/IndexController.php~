<?php

/**
 * Description of StickyNotesController
 *
 * @author Arian Khosravi <arian@bigemployee.com>, <@ArianKhosravi>
 */
// module/StickyNotes/src/StickyNotes/Controller/StickyNotesController.php:

namespace Outstation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Outstation\Form\OutstationForm;

class IndexController extends AbstractActionController {

    

    public function indexAction() {
        
       // $form = new OutstationForm();
        $request  = $this->getRequest();
        if($request->isPost()){
               $posted_data = $request->getPost();
               $posted_data = $posted_data->toArray();
               
        //echo '<pre>';print_r($posted_data);die;
            return $this->forward()->dispatch('Outstation/Controller/Index', [
                                    'action'     => 'car-list',
                                    'myObject'   => $post,
                                  ]);
        }
        
        return new ViewModel();
    }
    
    
    /** @Comment : Car listing page.
     */
    public function carListAction(){
        $cabType = $this->getServiceLocator()->get('CabTypeTable');
        $CityDistance = $this->getServiceLocator()->get('CityDistanceTable');
       
          
        $request = $this->getRequest();       
	$data = $request->getPost();
//      echo '<pre>';print_r($data);die();
        $booking_type_id = 104;
        $cabTypeList = $cabType->getCabType($data['outstation_From_cityid'][0], $booking_type_id);
        //echo '<pre>';print_r($cabTypeList);die();
        if($this->getRequest()->getPost('search')!=''){
              $data = $request->getPost();
                            
               //$daa  = $data['outstationFrom']; 
               //echo '<pre>';print_r($daa);die();
                /*$arrval['outstation']        = $posteddata['outstation'];
                $arrval['outstationFrom']    = $posteddata['outstationFrom'];
                $arrval['outstationTo']      = $posteddata['outstationTo'];
                $arrval['departure_date']    = $posteddata['departure_date'];
                $arrval['hours']             = $posteddata['hours'];
                $arrval['min']               = $posteddata['min'] ;
                $arrval['tour_days']         = $posteddata['tour_days'];
                $arrval['adults']            = $posteddata['adults'];
                $arrval['childs']            = $posteddata['childs'];
                $arrval['luggage']           = $posteddata['luggage'];
                $arrval['cars']              = $posteddata['cars'];
                $arrval['pickup']            = $posteddata['pickup'];
                $arrval['flight_no']         = $posteddata['flight_no'];
                $arrval['flight_hour']       = $posteddata['flight_hour'];
                $arrval['flight_min']        = $posteddata['flight_min'];
                $arrval['pickup_address']    = $posteddata['pickup_address'];                               
                echo '<pre>';print_r($arrval);die(); */
            echo '<pre>';print_r(($data));die();   
        }
      
       return new ViewModel(array('postdata' => $data, 'cabType'=>$cabTypeList,'citydistance'=>$CityDistance));
    }

    /** Author : Mohit verma
     */
    public function startdestinationAction(){         
        $cities = $this->getServiceLocator()->get('CitiesTable');
        $from_city_name  = $this->getRequest()->getPost('start_destination');
         $cityname = $cities->getCityNameFromName($from_city_name);
         //echo "<pre />"; print_r($cityname); die;
          /* $cityName = '<ul id="country-list">';
             if($from_city_name!=''){
             $cityname = $cities->getCityNameFromName($from_city_name);
            foreach($cityname as $city){
               $cityName .= '<li onClick=selectCountry('.$city['city_name'].');>'.$city["city_name"].'</li>';
            }
            $cityName .="</ul>";
        } echo $cityName ; die; */   
         $viewModel = new ViewModel(array('cityname'=>$cityname));
           $viewModel->setTerminal(true);
          return $viewModel;

    }
  
    
    
    public function bookingpaymentdetailAction(){
        echo $base_comb_id = $this->getRequest()->getPost('base_comb_id'); die;
    }
    
    
    
    }