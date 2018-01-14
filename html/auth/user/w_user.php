<?php
if (isset($_POST['f_submit_user'])) {

    $f_id_auth_user=filter_input(INPUT_POST,'f_id_auth_user',FILTER_SANITIZE_NUMBER_INT);
    $f_nom=filter_input(INPUT_POST,'f_nom',FILTER_SANITIZE_SPECIAL_CHARS);
    $f_prenom=filter_input(INPUT_POST,'f_prenom',FILTER_SANITIZE_SPECIAL_CHARS);
    $f_user=filter_input(INPUT_POST,'f_user',FILTER_SANITIZE_SPECIAL_CHARS);
    $f_mail=filter_input(INPUT_POST,'f_mail',FILTER_SANITIZE_SPECIAL_CHARS);
    $f_passwd=$_POST['f_passwd'];
    $f_type=filter_input(INPUT_POST,'f_type',FILTER_SANITIZE_SPECIAL_CHARS);

    $connSQL=new DB();
    if ($_POST['f_id_auth_user']) { // UPDATE
        $connSQL->bind('f_id_auth_user',$f_id_auth_user);
        if (!empty($f_passwd)) {
            $libpasswd='passwd=PASSWORD(:f_passwd),';
            $connSQL->bind('f_passwd',$f_passwd);
        } else {
            $libpasswd='';
        }
        $lib='
            UPDATE auth_user SET
                nom=:f_nom,
                prenom=:f_prenom,
                user=:f_user,
                mail=:f_mail,
                '.$libpasswd.'
                type=:f_type
            WHERE
                id_auth_user=:f_id_auth_user';
    } else { // INSERT
        $connSQL->bind('f_passwd',$f_passwd);
        $lib='INSERT INTO auth_user (
                nom,
                prenom,
                user,
                mail,
                passwd,
                type
            )
            VALUES (
                :f_nom,
                :f_prenom,
                :f_user,
                :f_mail,
                PASSWORD(:f_passwd),
                :f_type
            )';
    }
    $connSQL->bind('f_nom',$f_nom);
    $connSQL->bind('f_prenom',$f_prenom);
    $connSQL->bind('f_user',$f_user);
    $connSQL->bind('f_mail',$f_mail);
    $connSQL->bind('f_type',$f_type);
    $connSQL->query($lib);
}
?>
