 $(document).ready(function(){   
 
	$("#outstationFrom").keyup(function(){
            
		$.ajax({
		type: "POST",
		url: baseURI+"/outstation/startdestination",
		data:{start_destination:$(this).val()},
		beforeSend: function(){
			$("#outstationFrom").css("background","#FFF url("+baseURI+"/images/LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			//$("#outstationFrom").css("background","#FFF");
		}
		});
	});
        
        $(".suggesstionbox").keyup(function(){
             var This_id = $(this).attr("id");   
             var currentid_arr  = This_id.split('_');
             var second_val = currentid_arr[1];
             var third_val = currentid_arr[2];
		$.ajax({
		type: "POST",
                async : false,
		url: baseURI+"/outstation/startdestination",
		data:{start_destination:$(this).val()},
		beforeSend: function(){
			$("#"+This_id).css("background","#FFF url("+baseURI+"/images/LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
                   // alert("#suggesstionbox_"+second_val+"_"+third_val);
			$("#suggesstionbox_"+second_val+"_"+third_val).show();
			$("#suggesstionbox_"+second_val+"_"+third_val).html(data);
			//$("#outstationFrom").css("background","#FFF");
		}
		});
	});
});
//To select country name
