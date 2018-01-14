<?php
if (isset($_GET['f_id_config_server']) && isset($_GET['f_id_config_role'])) {
    $f_id_config_role=filter_input(INPUT_GET,'f_id_config_role',FILTER_SANITIZE_NUMBER_INT);
    $f_id_config_server=filter_input(INPUT_GET,'f_id_config_server',FILTER_SANITIZE_NUMBER_INT);

    $connSQL=new DB();
    $lib='SElECT
            crs.id_config_role,
            crs.id_config_server,
            cs.server_name,
            cs.server_description
        FROM
            config_role_server crs
                LEFT JOIN config_server cs
                    ON crs.id_config_server=cs.id_config_server
        WHERE crs.id_config_role=:f_id_config_role
        AND crs.id_config_server=:f_id_config_server';

    $connSQL->bind('f_id_config_role',$f_id_config_role);
    $connSQL->bind('f_id_config_server',$f_id_config_server);

    $cur_role_server=$connSQL->row($lib);
}
?>
