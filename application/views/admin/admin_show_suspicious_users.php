<?php
if(sizeof($obj_list) > 0)
{
	
?>
<table class="admin-show-client-issues-table">
<tr>
<td class="admin-show-client-issues-td">ID</td>
<td class="admin-show-client-issues-td">CREATED</td>
<td class="admin-show-client-issues-td">USER ID</td>
<td class="admin-show-client-issues-td">CELLPHONE</td>
<td class="admin-show-client-issues-td">USER_OBJ</td>
<td class="admin-show-client-issues-td">PAYLOAD</td>
<td class="admin-show-client-issues-td">BASE_PAYLOAD</td>

</tr>
<?php 	
	
	
	foreach ($obj_list as $obj_list_tmp)
	{
		
?>
<tr>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['_id']?></td>
<td class="admin-show-client-issues-td"><?php echo date("d/m/Y H:i:s",$obj_list_tmp['created_time']->sec)?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['user_id']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['cellphone']?></td>
<td class="admin-show-client-issues-td"><?php echo json_encode($obj_list_tmp['user_obj']);?></td>
<td class="admin-show-client-issues-td"><?php echo json_encode($obj_list_tmp['payload_obj']);?></td>
<td class="admin-show-client-issues-td"><?php echo json_encode($obj_list_tmp['base_payload_obj']);?></td>

</tr>


<?php
	}
?>
</table>
<?php  	
}
else 
{
?>
No records found
<?php 
}

?>