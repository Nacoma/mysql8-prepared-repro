<?php

require __DIR__ . '/vendor/autoload.php';

$bindings = ['event', 58, 'event'];


$runBench = function (PDO $pdo, string $fmt, $f) use ($bindings): void {
    $stmt = $pdo->prepare(<<<SQL
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
SQL
    );
    $time = get_execution_time(fn() => $stmt->execute($bindings));

    echo sprintf($fmt, $time) . "\n";

    $stmt = $pdo->prepare(<<<SQL
EXPLAIN SELECT o.*
FROM _orders o
JOIN _line_items li on o.id = li.order_id
JOIN _registrations rs ON rs.id = li.discr_id AND li.discr_type = ?
JOIN _registrants r on rs.registrant_id = r.id
WHERE
    r.user_id = ?
and li.discr_type = ?
GROUP BY o.id
LIMIT 1;
SQL
    );

    $stmt->execute($bindings);

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    fputcsv($f, array_keys($items[0]));

    foreach ($items as $item) {
        fputcsv($f, $item);
    }

    fclose($f);
};

$withEmulatePrepares = create_pdo_instance(true);
$withoutEmulatePrepares = create_pdo_instance(false);

$withEmulateF = fopen(__DIR__ . '/tmp/with_emulate_prepares.csv', 'w+');
$withoutEmulateF = fopen(__DIR__ . '/tmp/without_emulate_prepares.csv', 'w+');

$runBench($withEmulatePrepares, 'ATTR_EMULATE_PREPARES: ON - %f', $withEmulateF);
$runBench($withoutEmulatePrepares, 'ATTR_EMULATE_PREPARES: OFF  - %f', $withoutEmulateF);

exit(1);
