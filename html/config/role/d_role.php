<table border="0" cellpadding="0" cellspacing="0" id="table_role" class="table_admin">
<thead>
<tr>
    <th><?php echo ROLE ?></th>
    <th><?php echo DESC ?></th>
</tr>
</thead>
<tbody>
<?php


for ($i=0; $i<$cpt_role;$i++) {
    echo '
    <tr>
        <td><a href="index.php?module=config&amp;component=role&amp;f_id_config_role='.$all_role[$i]->id_config_role.'">'.$all_role[$i]->role.'</a></td>
        <td>'.$all_role[$i]->role_description.'</td>
    </tr>';
}
?>
</tbody>
</table>
