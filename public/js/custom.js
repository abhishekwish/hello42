
						 $(window).load(function() {			
						  $("#flexiselDemo1").flexisel({
							visibleItems: 3,
							animationSpeed: 1000,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: { 
								portrait: { 
									changePoint:481,
									visibleItems: 1
								},
								tablet: { 
									changePoint:769,
									visibleItems: 2
								}
							}
						});
						
								
						  $("#flexiselDemo2").flexisel({
							visibleItems: 3,
							animationSpeed: 1000,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: { 
								portrait: { 
									changePoint:480,
									visibleItems: 1
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 2
								},
								tablet: { 
									changePoint:769,
									visibleItems: 2
								}
							}
						});
								
						  $("#flexiselDemo3").flexisel({
							visibleItems: 8,
							animationSpeed: 1000,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: {
								portrait: { 
									changePoint:481,
									visibleItems: 2
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 4
								},
								tablet: { 
									changePoint:769,
									visibleItems: 4
								}
							}
						});
						
								
						  $("#flexiselDemo6").flexisel({
							visibleItems: 3,
							animationSpeed: 1000,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: {
								portrait: { 
									changePoint:481,
									visibleItems: 2
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 2
								},
								tablet: { 
									changePoint:769,
									visibleItems: 2
								}
							}
						});
						
						 $("#flexiselDemo7").flexisel({
							visibleItems: 3,
							animationSpeed: 1000,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: {
								portrait: { 
									changePoint:481,
									visibleItems: 2
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 2
								},
								tablet: { 
									changePoint:769,
									visibleItems: 2
								}
							}
						});
						
						$("#flexiselDemo4").flexisel({
							visibleItems: 3,
							animationSpeed: 400,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: {
								portrait: { 
									changePoint:481,
									visibleItems: 2
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 4
								},
								tablet: { 
									changePoint:769,
									visibleItems: 4
								}
							}
						});
						
						$("#flexiselDemo5").flexisel({
							visibleItems: 3,
							animationSpeed: 400,
							autoPlay: true,
							autoPlaySpeed: 5000,    		
							pauseOnHover:true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: {
								portrait: { 
									changePoint:481,
									visibleItems: 2
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 4
								},
								tablet: { 
									changePoint:769,
									visibleItems: 4
								}
							}
						});
						});

	
		
	$(function(){
		// Calling Login Form
		$("#login_form").click(function(){
			$(".login_form").show();
			$(".forgot_form").hide();
			//$(".forgot_login").hide();
			return false;
		});


// Calling forgot Form
		$("#forgot_form").click(function(){
			$(".forgot_form").show();
			//$(".social_login").hide();
			$(".login_form").hide();
			//$(".user_register").hide();
			//$(".header_title").text('Forgot Password');
			return false;
		});

		// Calling Register Form
		//$("#register_form").click(function(){
			//$(".social_login").hide();
			//$(".user_register").show();
			//$(".header_title").text('Register');
		//	$(".forgot_login").hide();
			//return false;
		//});

		// Going back to Social Forms
		$(".back_btn").click(function(){
			$(".forgot_form").hide();
			//$(".user_register").hide();
			$(".login_form").show();
			//$(".forgot_login").hide();
			//$(".header_title").text('Login');
			return false;
		});

	});
	
	
	$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        if($(this).attr("value")=="website"){
            $(".box").not(".mobileaap").hide();
			$(".box").not(".callcenter").hide();
            $(".website").show();
        }
        if($(this).attr("value")=="android"){
            $(".box").not(".website").hide();
			 $(".box").not(".callcenter").hide();
            $(".mobileaap").show();
        }
        if($(this).attr("value")=="callcenter"){
            $(".box").not(".website").hide();
			 $(".box").not(".mobileaap").hide();
            $(".callcenter").show();
        }
    });
});




$(document).ready(function(){
    $("div.item").tooltip();
  
 $("#outstation_icon").mouseover(function(){
        $("#text_outstation").show();
    });
$("#outstation_icon").mouseout(function(){
        $("#text_outstation").hide();
    });
	

	$("#pointtopoint_icon").mouseover(function(){
        $("#text_pointtopoint").show();
    });
$("#pointtopoint_icon").mouseout(function(){
        $("#text_pointtopoint").hide();
    });
	
	
	$("#local_icon").mouseover(function(){
        $("#text_local").show();
    });
$("#local_icon").mouseout(function(){
        $("#text_local").hide();
    });
	
	
	
	$("#transfer_icon").mouseover(function(){
        $("#text_transfer").show();
    });
$("#transfer_icon").mouseout(function(){
        $("#text_transfer").hide();
    });
/*$("#pickup_now").click(function(){
        $("#datetime").hide();
    });
$("#pickup_later").click(function(){
        $("#datetime").show();
    });	
    */
   /** Mohd Emadullah, on 01/06/17  **/
    $("input[type=radio]").click(function () {
        if($(this).prop("checked")) {
            var val= $("input[name='pickup']:checked").val();
            if(val=='Pick Now'){
                $("#datetime").hide();                
                
            }else if(val=='Book Later'){
                $("#datetime").show();             
                             
            }
        }
    });
    
    /** Mohd Emadullah, on 02/06/17 **/
    /*var dateToday = new Date();
    var year = dateToday.getFullYear();
     
    
    $("#pointLaterDate" ).datepicker({              
        changeMonth: true,
        changeYear: true,
        yearRange: '2000:'+year,
        //dateFormat : 'dd-mm-yy',
	//timeFormat: "HH:mm:ss",
        //timeFormat: "HH:mm",
        defaultDate: new Date(),
        minDate: dateToday
    });
    */
    
    
	
 });
 
 
 
 	$(function () {
    var sidebar = $('.topbar');
    var top = sidebar.offset().top - parseFloat(sidebar.css('margin-top'));

    $(window).scroll(function (event) {
      var y = $(this).scrollTop();
      if (y >= top) {
        sidebar.addClass('fixed');
      } else {
        sidebar.removeClass('fixed');
      }
    });
});

function togglemodify(id, btn_id) 
{	
    var btnId = btn_id.v
    $("#modify_search").slideToggle(500);
    if ($("#btnModify").val() == "Modify Search (+)") {
        $("#btnModify").val("Modify Search (-)");
    }
    else {
        $("#btnModify").val("Modify Search (+)");
    }

}

/** Mohd Emadullah, on 02/06/17 **/
$(document).ready(function () {
    
    var dateToday = new Date();
    $("#datetimepicker").kendoDateTimePicker({
        //value: new Date(),
        format: "dd/MM/yyyy hh:mm tt",
        min: dateToday,
        interval: 05,
    }).attr("readonly", "readonly");
    
    
});

      
      
    


