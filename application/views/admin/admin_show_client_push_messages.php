<script>
$(document).ready(function(){
	
  $("#create_new_message_button").click(function(){
	  $("#new_message_cont").show();
  });


  $("#save_button").click(function(){


	  var title = $("#title").val();
	  var body = $("#body").val();
	  var is_popup = $('#is_popup').prop('checked');
	  var is_notification = $('#is_notification').prop('checked');
      var user_ids =  $("#user_id").val();

      var user_ids_arr = user_ids.split(",");

      var counter;


      for(var i=0;i<user_ids_arr.length;i++)
      {

      var  user_id = user_ids_arr[i];
     // alert(user_id);

	  var data = {
			  title:title,
			  body:body,
			  is_popup:is_popup,
			  is_notification:is_notification,
			  user_id:user_id

					};
            if (user_id!=0) /*Oren:remove this for broadcast notif*/
            {
			$.ajax({
				   url: '/ajax_admin_show_client_push_messages' ,
				   type: "post",
				   data:data,

				   async: false,
				   success: function(data) {
		 	      // alert(data);


			   	   	data = jQuery.parseJSON(data);

		 			if(data['message'] == 'success')
		 			{

		 			 	//alert('saved');
		 			 location.reload();

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
      }
      if (i==user_ids_arr.length)
        alert('saved ' + i);

  });






});
</script>


<button id="create_new_message_button" class="admin_menu_buttons">Create A New Message</button>

<br><br>

<div id="new_message_cont" style="display:none;">
Title
<br>
<input type="text" id="title">
<br><br>

Body
<br>
<textarea id="body"></textarea>
<br><br>

<input type="checkbox" id="is_popup" checked="checked"> Is Popup
<br><br>

<input type="checkbox" id="is_notification" checked="checked"> Is Notification
<br><br>


User Id
<br> 
(leave empty for global message sent to everybody or use a comma seperated for multi users)
<br>
<input type="text" id="user_id" >
<br><br>

<button type="button" id="save_button">SAVE</button>

</div>


<br>

<?php




if(sizeof($obj_list) > 0)
{
?>
<table class="admin-show-client-issues-table">
<tr>
<td class="admin-show-client-issues-td">ID</td>
<td class="admin-show-client-issues-td">CREATED</td>
<td class="admin-show-client-issues-td">TITLE</td>
<td class="admin-show-client-issues-td">BODY</td>
<td class="admin-show-client-issues-td">IS POPUP</td>
<td class="admin-show-client-issues-td">IS NOTIFICATION</td>
<td class="admin-show-client-issues-td">USER ID</td>
</tr>
<?php 	
	
	
	foreach ($obj_list as $obj_list_tmp)
	{
		
?>
<tr>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['_id']?></td>
<td class="admin-show-client-issues-td"><?php echo date("d/m/Y H:i:s",$obj_list_tmp['created_time']->sec)?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['title']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['body']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['is_popup']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['is_notification']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['user_id']?></td>
</tr>


<?php
	}
?>
</table>
<?php  	
}

?>