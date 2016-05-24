<script>
$(document).ready(function(){
	
  $("#edit_client_texts_button").click(function(){
    window.location = "/admin_edit_client_texts";
  });

  $("#edit_client_general_params_button").click(function(){
	    window.location = "/admin_edit_client_general_parms";
	  });

  $("#show_client_locations_button").click(function(){
	    window.location = "/admin_show_client_locations";
	  });

  
  $("#show_client_issues_button").click(function(){
	    window.location = "/admin_show_client_issues";
	  });

  $("#show_client_push_messages_button").click(function(){
	    window.location = "/admin_show_client_push_messages";
	  });

  $("#admin_show_suspicious_users_button").click(function(){
	    window.location = "/admin_show_suspicious_users";
	  });

  
  
  $("#logoff_admin_button").click(function(){
	    window.location = "/admin_logoff";
	  });

   $("#fix_duplicate_payload_button").click(function(){
	    window.location = "/admin_fix_duplicate_payload";
	  });

      $("#number_of_users_percountry_button").click(function(){
	    window.location = "/admin_number_of_users_per_country";
	  });


  
});
</script>



<button id="edit_client_texts_button" class="admin_menu_buttons">Edit Client Texts</button>
<br><br>
<button id="edit_client_general_params_button" class="admin_menu_buttons">Edit General Parameters</button>
<br><br>
<button id="show_client_locations_button" class="admin_menu_buttons">Show Client Locations</button>
<br><br>
<button id="show_client_issues_button" class="admin_menu_buttons">Show Client Issues</button>
<br><br>
<button id="show_client_push_messages_button" class="admin_menu_buttons">Client Push Messages</button>
<br><br>
<button id="admin_show_suspicious_users_button" class="admin_menu_buttons">Suspicious Users</button>


<br><br><br><br>
<button id="logoff_admin_button" class="admin_menu_buttons">Logoff Admin</button>
<button id="fix_duplicate_payload_button" class="admin_menu_buttons">fix dup admin</button>

<button id="number_of_users_percountry_button" class="admin_menu_buttons">number of users</button>
