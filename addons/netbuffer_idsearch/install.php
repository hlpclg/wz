<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );

$sql = "
select now();
";
pdo_run ( $sql );