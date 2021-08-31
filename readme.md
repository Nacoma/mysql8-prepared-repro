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
ATTR_EMULATE_PREPARES: OFF - 1.699921
ATTR_EMULATE_PREPARES: ON  - 0.047665
ATTR_EMULATE_PREPARES: OFF - 1.667094
ATTR_EMULATE_PREPARES: ON  - 0.046499
ATTR_EMULATE_PREPARES: OFF - 1.695784
ATTR_EMULATE_PREPARES: ON  - 0.045361
ATTR_EMULATE_PREPARES: OFF - 1.696254
ATTR_EMULATE_PREPARES: ON  - 0.045783
ATTR_EMULATE_PREPARES: OFF - 1.686849
ATTR_EMULATE_PREPARES: ON  - 0.045870
ATTR_EMULATE_PREPARES: OFF - 1.704923
ATTR_EMULATE_PREPARES: ON  - 0.046515
ATTR_EMULATE_PREPARES: OFF - 1.702254
ATTR_EMULATE_PREPARES: ON  - 0.045991
ATTR_EMULATE_PREPARES: OFF - 1.689755
```