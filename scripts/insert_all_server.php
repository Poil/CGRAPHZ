<?php
include('../config/config.php');

$connSQL=new DB();
$all_server=$connSQL->query('SELECT * FROM config_server ORDER BY server_name');
$cpt_server=count($all_server);

/* Listing des serveurs présent dans le RRD DIR et pas déjà affectés */
$allDatadir=getAllDatadir();
$filelist=array();
foreach($allDatadir as $datadir){
    $filelist=array_merge(array_values(array_diff(scandir($datadir), array('..', '.', 'lost+found'))), $filelist);
}

$lib='
CREATE TEMPORARY TABLE server_list (
    `server_name` varchar(45) NOT NULL default \'\'
)';
$connSQL->query($lib);


$find='0';
$lib= 'INSERT INTO server_list (server_name) VALUES (';
$cpt_filelist=count($filelist);
for($i=0; $i<$cpt_filelist; $i++) {
    if (strpos($filelist[$i],':')==false ) {
        if($find=='1')  {
            $lib.=" ), (";
        }
        $lib.= '\''.$filelist[$i].'\'';
        $find='1';
    }
}
$lib.=' )';

if ($find=='1') {
    $connSQL->query($lib);

    /////////////////////////////////////
    // Insert
    $lib = 'INSERT INTO config_server (server_name, server_description) (
          SELECT server_name, server_name as server_description
                 FROM server_list
          WHERE server_name NOT IN (
                    SELECT server_name FROM config_server
              ) ORDER BY server_name
        )';
    $connSQL->query($lib);

    /////////////////////////////////////
    // Purge removed server
    $lib='
        SELECT id_config_server
        FROM config_server
        WHERE server_name NOT IN (
            SELECT server_name FROM server_list
        ) ORDER BY server_name';

    $all_deleted_server=$connSQL->query($lib);

    foreach ($all_deleted_server as $cur_server) {
        $connSQL=new DB();

        $connSQL->bind('id_config_server',$cur_server->id_config_server);
        $lib='DELETE FROM config_role_server WHERE id_config_server=:id_config_server';
        $connSQL->query($lib);

        $connSQL->bind('id_config_server',$cur_server->id_config_server);
        $lib='DELETE FROM config_environment_server WHERE id_config_server=:id_config_server';
        $connSQL->query($lib);

        $connSQL->bind('id_config_server',$cur_server->id_config_server);
        $lib='DELETE FROM config_server_project WHERE id_config_server=:id_config_server';
        $connSQL->query($lib);

        $connSQL->bind('id_config_server',$cur_server->id_config_server);
        $lib='DELETE FROM config_server WHERE id_config_server=:id_config_server';
        $res = $connSQL->query($lib);

    }
}

?>
