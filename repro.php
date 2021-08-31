<?php

require __DIR__ . '/vendor/autoload.php';

$query = <<<SQL
SELECT o.*
FROM _orders o
JOIN _line_items li on o.id = li.order_id
JOIN _registrations rs ON rs.id = li.discr_id AND li.discr_type = ?
JOIN _registrants r on rs.registrant_id = r.id
WHERE
    r.user_id = ?
and li.discr_type = ?
GROUP BY o.id
LIMIT 1;
SQL;

$bindings = ['event', 58, 'event'];

$withEmulatePrepares = create_pdo_instance(true);
$withoutEmulatePrepares = create_pdo_instance(false);

for ($x = 0; $x < 10; $x++) {
    $stmt = $withEmulatePrepares->prepare($query);
    $time = get_execution_time(fn () => $stmt->execute($bindings));
    echo sprintf("ATTR_EMULATE_PREPARES: ON  - %f\n", $time);

    $stmt = $withoutEmulatePrepares->prepare($query);
    $time = get_execution_time(fn () => $stmt->execute($bindings));
    echo sprintf("ATTR_EMULATE_PREPARES: OFF - %f\n", $time);
}

exit(1);
