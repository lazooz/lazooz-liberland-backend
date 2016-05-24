<script>
$(document).ready(function(){
	
  $("#client_current_build_num_button").click(function(){

	  var key = 'client_current_build_num';
	  var value =  $("#client_current_build_num").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_general_parms' ,
		   type: "post",
		   data:data,
		 
		   async: false,
		   success: function(data) {
   	      // alert(data);

		
	   	   	data = jQuery.parseJSON(data);

   			if(data['message'] == 'success')
   			{

   			 	alert('saved');
   			 location.reload();
   				
   	   		}
   			else
   			{
				
   				alert('error saving');
   	   		}   

		   }
		});

  });




  $("#client_min_build_num_button").click(function(){

	  var key = 'client_min_build_num';
	  var value =  $("#client_min_build_num").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_general_parms' ,
		   type: "post",
		   data:data,
		 
		   async: false,
		   success: function(data) {
   	      // alert(data);

		
	   	   	data = jQuery.parseJSON(data);

   			if(data['message'] == 'success')
   			{

   			 	alert('saved');
   			 location.reload();
   				
   	   		}
   			else
   			{
				
   				alert('error saving');
   	   		}   

		   }
		});

  });

  




  $("#server_version_button").click(function(){

	  var key = 'server_version';
	  var value =  $("#server_version").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_general_parms' ,
		   type: "post",
		   data:data,
		 
		   async: false,
		   success: function(data) {
   	      // alert(data);

		
	   	   	data = jQuery.parseJSON(data);

   			if(data['message'] == 'success')
   			{

   			 	alert('saved');
   			 location.reload();
   				
   	   		}
   			else
   			{
				
   				alert('error saving');
   	   		}   

		   }
		});

  });
  




  $("#zooz_to_dolar_conversion_rate_button").click(function(){

	  var key = 'zooz_to_dolar_conversion_rate';
	  var value =  $("#zooz_to_dolar_conversion_rate").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_general_parms' ,
		   type: "post",
		   data:data,
		 
		   async: false,
		   success: function(data) {
   	      // alert(data);

		
	   	   	data = jQuery.parseJSON(data);

   			if(data['message'] == 'success')
   			{

   			 	alert('saved');
   			 location.reload();
   				
   	   		}
   			else
   			{
				
   				alert('error saving');
   	   		}   

		   }
		});

  });


  
  $("#critical_mass_tab_button").click(function(){

	  var key = 'critical_mass_tab';
	  var value =  $("#critical_mass_tab").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_general_parms' ,
		   type: "post",
		   data:data,
		 
		   async: false,
		   success: function(data) {
   	      // alert(data);

		
	   	   	data = jQuery.parseJSON(data);

   			if(data['message'] == 'success')
   			{

   			 	alert('saved');
   			 location.reload();
   				
   	   		}
   			else
   			{
				
   				alert('error saving');
   	   		}   

		   }
		});

  });  


  
  $("#zooz_reward_for_recommendation_user_button").click(function(){

	  var key = 'zooz_reward_for_recommendation_user';
	  var value =  $("#zooz_reward_for_recommendation_user").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_general_parms' ,
		   type: "post",
		   data:data,
		 
		   async: false,
		   success: function(data) {
   	      // alert(data);

		
	   	   	data = jQuery.parseJSON(data);

   			if(data['message'] == 'success')
   			{

   			 	alert('saved');
   			 location.reload();
   				
   	   		}
   			else
   			{
				
   				alert('error saving');
   	   		}   

		   }
		});

  });  
  
});
</script>

client_current_build_num
<br>
<input type="text" value="<?php echo $client_current_build_num;?>" id="client_current_build_num">
<br>
<button id="client_current_build_num_button">Save</button>

<br><br>


client_min_build_num
<br>
<input type="text" value="<?php echo $client_min_build_num;?>" id="client_min_build_num">
<br>
<button id="client_min_build_num_button">Save</button>

<br><br>



server_version
<br>
<input type="text" value="<?php echo $server_version;?>" id="server_version">
<br>
<button id="server_version_button">Save</button>

<br><br>



zooz_to_dolar_conversion_rate
<br>
<input type="text" value="<?php echo $zooz_to_dolar_conversion_rate;?>" id="zooz_to_dolar_conversion_rate">
<br>
<button id="zooz_to_dolar_conversion_rate_button">Save</button>

<br><br>


critical_mass_tab
<br>
<input type="text" value="<?php echo $critical_mass_tab;?>" id="critical_mass_tab">
<br>
<button id="critical_mass_tab_button">Save</button>

<br><br>

zooz_reward_for_recommendation_user
<br>
<input type="text" value="<?php echo $zooz_reward_for_recommendation_user;?>" id="zooz_reward_for_recommendation_user">
<br>
<button id="zooz_reward_for_recommendation_user_button">Save</button>

<br><br>

