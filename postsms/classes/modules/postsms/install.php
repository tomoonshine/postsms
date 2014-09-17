<?php
 
$INFO = Array();
 
$INFO['name'] = "postsms";
$INFO['filename'] = "modules/postsms/class.php";
$INFO['config'] = "1";
$INFO['ico'] = "postsms";
$INFO['default_method'] = "getProvider";
$INFO['default_method_admin'] = "mainpage";
 
$INFO['func_perms'] = "";
$INFO['func_perms/view'] = "Просмотр данных";
$INFO['func_perms/edit'] = "Редактирование данных";

$COMPONENTS = array();
 
$COMPONENTS[0] = "./classes/modules/news/__admin.php";
$COMPONENTS[1] = "./classes/modules/news/class.php";
$COMPONENTS[2] = "./classes/modules/news/i18n.php";
$COMPONENTS[3] = "./classes/modules/news/lang.php";
$COMPONENTS[4] = "./classes/modules/news/permissions.php";
 
 
?>