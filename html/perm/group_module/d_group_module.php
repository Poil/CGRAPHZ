<table border="0" cellpadding="0" cellspacing="0" id="table_group_project" class="table_admin">
<thead>
<tr>
	<th><?php echo MODULE ?></th>
	<th><?php echo COMPONENT ?></th>
	<th><?php echo MENU_NAME ?></th>
</tr>
</thead>
<tbody>
<?php 

for ($i=0; $i<$cpt_group_module;$i++) {
	echo '
	<tr>
		<td><a href="index.php?module=auth&amp;component=group&amp;f_id_auth_group='.$_GET['f_id_auth_group'].'&amp;f_id_perm_module='.$all_group_module[$i]->id_perm_module.'">'.$all_group_module[$i]->module.'</a></td>
		<td><a href="index.php?module=auth&amp;component=group&amp;f_id_auth_group='.$_GET['f_id_auth_group'].'&amp;f_id_perm_module='.$all_group_module[$i]->id_perm_module.'">'.$all_group_module[$i]->component.'</a></td>
		<td>'.$all_group_module[$i]->menu_name.'</td>
	</tr>';
}
?>
</tbody>
</table>
