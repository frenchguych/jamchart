<?php

require 'vendor/autoload.php';

$db = new \Medoo\Medoo([
    'database_type' => 'mysql',
    'database_name' => 'jamchart',
    'server' => 'localhost',
    'username' => 'jamchart',
    'password' => 'jamchart',
    'charset' => 'utf8',
]);

$monday = new DateTime('last monday');
$t0 = $monday->format('Y-m-d');
$monday->modify("-7 days");
$t1 = $monday->format('Y-m-d');
$monday->modify("-7 days");
$t2 = $monday->format('Y-m-d');
$monday->modify("-7 days");
$t3 = $monday->format('Y-m-d');
$monday->modify("-7 days");
$t4 = $monday->format('Y-m-d');

$db->query('DROP VIEW IF EXISTS t0');
$db->query(
    'CREATE VIEW t0 AS SELECT id, 2 * downloads + listened AS downloads FROM tracks WHERE date_retrieved = :date_retrieved',
    [
        ':date_retrieved' => $t0
    ]
);

$db->query('DROP VIEW IF EXISTS t1');
$db->query(
    'CREATE VIEW t1 AS SELECT id, 2 * downloads + listened AS downloads FROM tracks WHERE date_retrieved = :date_retrieved',
    [
        ':date_retrieved' => $t1
    ]
);

$db->query('DROP VIEW IF EXISTS t2');
$db->query(
    'CREATE VIEW t2 AS SELECT id, 2 * downloads + listened AS downloads FROM tracks WHERE date_retrieved = :date_retrieved',
    [
        ':date_retrieved' => $t2
    ]
);

$db->query('DROP VIEW IF EXISTS t3');
$db->query(
    'CREATE VIEW t3 AS SELECT id, 2 * downloads + listened AS downloads FROM tracks WHERE date_retrieved = :date_retrieved',
    [
        ':date_retrieved' => $t3
    ]
);

$db->query('DROP VIEW IF EXISTS t4');
$db->query(
    'CREATE VIEW t4 AS SELECT id, 2 * downloads + listened AS downloads FROM tracks WHERE date_retrieved = :date_retrieved',
    [
        ':date_retrieved' => $t4
    ]
);

$db->query('DROP VIEW IF EXISTS d');
$db->query(
    'CREATE VIEW d AS SELECT t0.id, IFNULL(t0.downloads, 0) - IFNULL(t1.downloads, 0) d0, IFNULL(t1.downloads, 0) - IFNULL(t2.downloads, 0) d1, IFNULL(t2.downloads, 0) - IFNULL(t3.downloads, 0) d2, IFNULL(t3.downloads, 0) - IFNULL(t4.downloads, 0) d3 FROM t0 LEFT JOIN t1 ON t1.id = t0.id LEFT JOIN t2 ON t2.id = t0.id LEFT JOIN t3 ON t3.id = t0.id LEFT JOIN t4 ON t4.id = t0.id'
);

$db->query('DROP VIEW IF EXISTS t');
$db->query(
    'CREATE VIEW t AS SELECT * FROM tracks WHERE date_retrieved = :date_retrieved',
    [
        'date_retrieved' => $t0
    ]
);

$db->query(
    'DELETE FROM charts WHERE date_retrieved = :date_retrieved',
    [
        'date_retrieved' => $t0
    ]
);
$db->query(
    'INSERT INTO charts SELECT @rownum := @rownum + 1 AS rownum, o.* FROM (SELECT DATE(:date_retrieved) date_retrieved, d.id, 4 * d.d0 + 3 * d.d1 + 2 * d.d2 + d.d3 AS score FROM d ORDER BY score DESC) o, (SELECT @rownum := 0) r',
    [
        'date_retrieved' => $t0
    ]
);

$db->query('DROP VIEW IF EXISTS c0');
$db->query(
    'CREATE VIEW c0 AS SELECT * FROM charts WHERE date_retrieved = :date_retrieved',
    [
        'date_retrieved' => $t0
    ]
);

$db->query('DROP VIEW IF EXISTS c1');
$db->query(
    'CREATE VIEW c1 AS SELECT * FROM charts WHERE date_retrieved = :date_retrieved',
    [
        'date_retrieved' => $t1
    ]
);

$db->query(
    'DELETE FROM movements WHERE date_retrieved = :date_retrieved',
    [
        'date_retrieved' => $t0
    ]
);

$db->query(
    'INSERT INTO movements SELECT c0.*, c1.rownum - c0.rownum AS move FROM c0 LEFT JOIN c1 ON c1.id = c0.id ORDER BY c0.rownum ASC'
);
