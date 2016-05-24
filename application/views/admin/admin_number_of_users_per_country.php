<script>
$(document).ready(function(){
	
  $("#create_new_message_button").click(function(){
	  $("#new_message_cont").show();
  });


  $("#save_button").click(function(){



	  var country = $("#country").val();






     // alert(user_id);

	  var data = {
			  country:country

					};
            if (1)
            {
			$.ajax({
				   url: '/ajax_admin_number_of_users_per_country' ,
				   type: "post",
				   data:data,

				   async: false,
				   success: function(data) {
		 	       alert(data);


			   	   	data = jQuery.parseJSON(data);

		 			if(data['message'] == 'success')
		 			{

		 			 	//alert('saved');
		 			 //location.reload();

		 	   		}
		 			else
		 			{

		 				alert('error saving '+user_id);
		 	   		}
                //    alert(i);

				   }
				});
                }
                else
                {
                  alert('user_id=0');
                }

  });






});
</script>


<button id="create_new_message_button" class="admin_menu_buttons">Check Users Per Country</button>

<br><br>

<div id="new_message_cont" style="display:none;">

Country
<br>
<textarea id="country"></textarea>
<br><br>



<button type="button" id="save_button">Check</button>

</div>


<br>

<?php




if(sizeof($obj_list) > 0)
{
?>
<table class="admin-show-client-issues-table">
<tr>
<td class="admin-show-client-issues-td">COUNTRY</td>

</tr>
<?php 	
	
	
	foreach ($obj_list as $obj_list_tmp)
	{
		
?>
<tr>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['country']?></td>

</tr>


<?php
	}
?>
</table>
<?php  	
}

?>