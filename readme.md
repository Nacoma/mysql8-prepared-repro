Major performance discrepancy between prepared statements and emulated prepared statements on MySQL8.

To run the benchmark demo:


```shell
docker-compose up
```

Sample output:

```
ATTR_EMULATE_PREPARES: OFF - 2.103839
ATTR_EMULATE_PREPARES: ON  - 0.047985
ATTR_EMULATE_PREPARES: OFF - 1.691186
ATTR_EMULATE_PREPARES: ON  - 0.047059
```

## MySQL8
Explain - Prepared
```tsv
id  select_type  table  partitions  type    possible_keys                                                                key                                             key_len  ref                    rows    filtered         Extra
1   SIMPLE       li                 index   _line_items_order_id_foreign,_line_items_discr_id_discr_type_order_id_index  _line_items_discr_id_discr_type_order_id_index  776                             926816  1.0000001192093  Using where; Using index; Using temporary
1   SIMPLE       o                  eq_ref  PRIMARY                                                                      PRIMARY                                         4        test.li.order_id       1       100              
1   SIMPLE       rs                 eq_ref  PRIMARY,_registrations_registrant_id_foreign                                 PRIMARY                                         4        test.li.discr_id       1       100              Using where
1   SIMPLE       r                  eq_ref  PRIMARY,_registrants_user_id_index                                           PRIMARY                                         4        test.rs.registrant_id  1       8.5316295623779  Using where
```

Explain - Emulated
```tsv
id  select_type  table  partitions  type    possible_keys                                                                key                                             key_len  ref               rows  filtered  Extra
1   SIMPLE       r                  ref     PRIMARY,_registrants_user_id_index                                           _registrants_user_id_index                      5        const             6970  100.00    Using index; Using temporary
1   SIMPLE       rs                 ref     PRIMARY,_registrations_registrant_id_foreign                                 _registrations_registrant_id_foreign            4        test.r.id         8     100.00    Using index
1   SIMPLE       li                 ref     _line_items_order_id_foreign,_line_items_discr_id_discr_type_order_id_index  _line_items_discr_id_discr_type_order_id_index  771      test.rs.id,const  1     100.00    Using where; Using index
1   SIMPLE       o                  eq_ref  PRIMARY                                                                      PRIMARY                                         4        test.li.order_id  1     100.00    
```

## MySQL5.7

Explain - Prepared
```tsv
id  select_type  table  partitions  type    possible_keys                                                                key                                             key_len  ref                rows  filtered  Extra
1   SIMPLE       r                  ref     PRIMARY,_registrants_user_id_index                                           _registrants_user_id_index                      5        const              6970  100       Using index; Using temporary; Using filesort
1   SIMPLE       rs                 ref     PRIMARY,_registrations_registrant_id_foreign                                 _registrations_registrant_id_foreign            4        repro.r.id         7     100       Using index
1   SIMPLE       li                 ref     _line_items_order_id_foreign,_line_items_discr_id_discr_type_order_id_index  _line_items_discr_id_discr_type_order_id_index  771      repro.rs.id,const  1     100       Using where; Using index
1   SIMPLE       o                  eq_ref  PRIMARY                                                                      PRIMARY                                         4        repro.li.order_id  1     100       
```

Explain - Emulated
```tsv
id  select_type  table  partitions  type    possible_keys                                                                key                                             key_len  ref                rows  filtered  Extra
1   SIMPLE       r                  ref     PRIMARY,_registrants_user_id_index                                           _registrants_user_id_index                      5        const              6970  100.00    Using index; Using temporary; Using filesort
1   SIMPLE       rs                 ref     PRIMARY,_registrations_registrant_id_foreign                                 _registrations_registrant_id_foreign            4        repro.r.id         7     100.00    Using index
1   SIMPLE       li                 ref     _line_items_order_id_foreign,_line_items_discr_id_discr_type_order_id_index  _line_items_discr_id_discr_type_order_id_index  771      repro.rs.id,const  1     100.00    Using where; Using index
1   SIMPLE       o                  eq_ref  PRIMARY                                                                      PRIMARY                                         4        repro.li.order_id  1     100.00    
```
