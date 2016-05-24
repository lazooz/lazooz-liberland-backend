<script>
$(document).ready(function(){
	
  $("#before_cellphone_validation_screen_text_button").click(function(){

	  var key = 'before_cellphone_validation_screen_text';
	  var value =  $("#before_cellphone_validation_screen_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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






  $("#before_shake_screen_text_button").click(function(){

	  var key = 'before_shake_screen_text';
	  var value =  $("#before_shake_screen_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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

  
  $("#whats_next_question_mark_text_button").click(function(){

	  var key = 'whats_next_question_mark_text';
	  var value =  $("#whats_next_question_mark_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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

  
  $("#disclaimer_screen_headline_text_button").click(function(){

	  var key = 'disclaimer_screen_headline_text';
	  var value =  $("#disclaimer_screen_headline_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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





  
  $("#disclaimer_screen_text_button").click(function(){

	  var key = 'disclaimer_screen_text';
	  var value =  $("#disclaimer_screen_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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




  
  $("#intro_screen_text_button").click(function(){

	  var key = 'intro_screen_text';
	  var value =  $("#intro_screen_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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



  
  $("#second_step_screen_text_button").click(function(){

	  var key = 'second_step_screen_text';
	  var value =  $("#second_step_screen_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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


  
  $("#popup_after_100_km_milestone_title_text_button").click(function(){

	  var key = 'popup_after_100_km_milestone_title_text';
	  var value =  $("#popup_after_100_km_milestone_title_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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

  
  
  $("#popup_after_100_km_milestone_text_button").click(function(){

	  var key = 'popup_after_100_km_milestone_text';
	  var value =  $("#popup_after_100_km_milestone_text").val();
    

    var data = { 
    	    key:key,
    	    value:value
			};
	
	$.ajax({
		   url: '/ajax_admin_edit_client_texts' ,
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

before_cellphone_validation_screen_text
<br>
<textarea class="admin_client_texts" id="before_cellphone_validation_screen_text"><?php echo $before_cellphone_validation_screen_text;?></textarea>
<br>
<button id="before_cellphone_validation_screen_text_button">Save</button>

<br><br>


before_shake_screen_text
<br>
<textarea class="admin_client_texts" id="before_shake_screen_text"><?php echo $before_shake_screen_text;?></textarea>
<br>
<button id="before_shake_screen_text_button">Save</button>

<br><br>

whats_next_question_mark_text
<br>
<textarea class="admin_client_texts" id="whats_next_question_mark_text"><?php echo $whats_next_question_mark_text;?></textarea>
<br>
<button id="whats_next_question_mark_text_button">Save</button>

<br><br>


disclaimer_screen_headline_text
<br>
<textarea class="admin_client_texts" id="disclaimer_screen_headline_text"><?php echo $disclaimer_screen_headline_text;?></textarea>
<br>
<button id="disclaimer_screen_headline_text_button">Save</button>

<br><br>



disclaimer_screen_text
<br>
<textarea class="admin_client_texts" id="disclaimer_screen_text"><?php echo $disclaimer_screen_text;?></textarea>
<br>
<button id="disclaimer_screen_text_button">Save</button>

<br><br>



intro_screen_text
<br>
<textarea class="admin_client_texts" id="intro_screen_text"><?php echo $intro_screen_text;?></textarea>
<br>
<button id="intro_screen_text_button">Save</button>

<br><br>



second_step_screen_text
<br>
<textarea class="admin_client_texts" id="second_step_screen_text"><?php echo $second_step_screen_text;?></textarea>
<br>
<button id="second_step_screen_text_button">Save</button>

<br><br>

popup_after_100_km_milestone_title_text
<br>
<textarea class="admin_client_texts" id="popup_after_100_km_milestone_title_text"><?php echo $popup_after_100_km_milestone_title_text;?></textarea>
<br>
<button id="popup_after_100_km_milestone_title_text_button">Save</button>

<br><br>


popup_after_100_km_milestone_text
<br>
<textarea class="admin_client_texts" id="popup_after_100_km_milestone_text"><?php echo $popup_after_100_km_milestone_text;?></textarea>
<br>
<button id="popup_after_100_km_milestone_text_button">Save</button>

<br><br>