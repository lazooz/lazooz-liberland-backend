<script>
$(document).ready(function(){

	

	 $("#report-issue-submit-button").click(function(){

		 location.reload();

	
	  });
	  

	
	$( "#issue_form" ).submit(function( event ) {
		  event.preventDefault();



		  var c = $( "#c" ).val() ;
		  var issue_subject =  $("#issue_subject").val();
		  var issue_desc =  $("#issue_desc").val();
	    

	    var data = { 
	    	    c:c,
	    	    issue_subject:issue_subject,
	    	    issue_desc:issue_desc
				};
		
		$.ajax({
			   url: '/ajax_client_report_issue' ,
			   type: "post",
			   data:data,
			 
			   async: false,
			   success: function(data) {
	   	      // alert(data);

			
		   	   	data = jQuery.parseJSON(data);

	   			if(data['message'] == 'success')
	   			{

	   			$( "#mobile-report-issue-form-cont" ).hide();
	   			$( "#mobile-report-issue-form-cont-2" ).show();

	   			 
	   	   		}
	   			else
	   			{
					
	   				//alert('error saving');
	   	   		}   

			   }
			});









		  
		});

	
	
 


  
});
</script>


<div class="mobile-wrapper">


<div class="mobile-headline">
SUBMIT ISSUE/BUG
</div>




<div class="mobile_spacer"></div>

<div class="mobile-report-issue-form-cont" id="mobile-report-issue-form-cont" style="display:block ;">

<form  id="issue_form" action="/client_report_issue/<?php echo $user_id;?>" method="post">
<input id="c" type="hidden" value="<?php echo $user_id;?>">

<select name="issue_subject" id="issue_subject">
<option value="">* Select subject</option>
<option value="bug">REPORT A BUG</option>
<option value="feature_suggestion">FEATURE SUGGESTION</option>
<option value="other">OTHER</option>

</select>

<div class="mobile_spacer"></div>

<textarea name="issue_desc" class="report-issue-desc" id="issue_desc" placeholder="Please elaborate"></textarea>

<div class="mobile_spacer"></div>
<div class="mobile_spacer"></div>

<button class="report-issue-submit-button" type="submit">SEND</button>

</form>

</div>

<div class="mobile-report-issue-form-cont" id="mobile-report-issue-form-cont-2" style="display: none;">




<div class="mobile-report-issue-submit-result">
Form was submited successfuly
</div>


<div class="mobile-report-issue-submit-result-another">
<button class="report-issue-submit-button" id="report-issue-submit-button">SUBMIT ANOTHER ISSUE</button>
</div>


</div>



</div>

