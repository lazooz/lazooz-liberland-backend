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
<td class="admin-show-client-issues-td">SUBJECT</td>
<td class="admin-show-client-issues-td">DESCRIPTION</td>
<td class="admin-show-client-issues-td">CLIENT VER</td>
<td class="admin-show-client-issues-td">SERVER VER</td>
</tr>
<?php 	
	
	
	foreach ($obj_list as $obj_list_tmp)
	{
		if(!isset($obj_list_tmp['cellphone']))
		{
			$obj_list_tmp['cellphone'] =  '';
		}
		
		if(!isset($obj_list_tmp['current_build_num']))
		{
			$obj_list_tmp['current_build_num'] =  '';
		}
		
		if(!isset($obj_list_tmp['server_version']))
		{
			$obj_list_tmp['server_version'] =  '';
		}
?>
<tr>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['_id']?></td>
<td class="admin-show-client-issues-td"><?php echo date("d/m/Y H:i:s",$obj_list_tmp['created']->sec)?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['user_id']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['cellphone']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['subject']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['desc']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['current_build_num']?></td>
<td class="admin-show-client-issues-td"><?php echo $obj_list_tmp['server_version']?></td>
</tr>


<?php
	}
?>
</table>
<?php  	
}

?>