<?php
echo '<select name="f_project">';
$perm_mod = new PERMS();
if ($perm_mod->perm_module('dashboard','view')) { 
  foreach ($all_project as $project) {
    if (intval(GET('f_id_config_project'))==$project->id_config_project) {
      $style=' style="font-weight: bold;" '; 
    } else { 
      $style=''; 
    }
		
    echo '<option '.$style.' value="'.$project->id_config_project.'">'.$project->project_description.'</option>';
	}
}
echo '</select>';


if ($perm_mod->perm_module('dashboard','search')) { 
  ?>
  <div id="f_form_find_server">
  <label for="f_find_server"><?php echo SEARCH ?>: <input type="text" id="f_find_server" name="f_find_server" /></label>
  </div>
  <div class="clearfix"></div>
  <script type="text/javascript">
  	jQuery('#f_form_find_server input[name="f_find_server"]').liveSearch({url: '<?php echo DIR_WEBROOT ?>/html/dashboard/project_list/ajax_server_wh_q.php' + '?f_q='});
  </script>
<?php } ?>
