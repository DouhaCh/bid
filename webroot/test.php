<?php

// Include the config file
require_once '../config/config.php';
require_once '../database.php';

mysql_query("UPDATE `newpenny`.`bug_types` SET `description` = 'bất kỳ những gì không hoạt động' WHERE `bug_types`.`id` =1 LIMIT 1");
mysql_query("UPDATE `newpenny`.`bug_types` SET `description` = 'rắc rối, khó sử dụng, gây bực mình, yêu cầu quá nhiều công sức, v.v' WHERE `bug_types`.`id` =2 LIMIT 1");
mysql_query("UPDATE `newpenny`.`bug_types` SET `description` = 'các chức năng mà bạn muốn 1bid.vn sẽ có trong tương lai' WHERE `bug_types`.`id` =3 LIMIT 1");
mysql_query("UPDATE `newpenny`.`bug_types` SET `description` = 'Góp ý chung' WHERE `bug_types`.`id` =4 LIMIT 1");

?>