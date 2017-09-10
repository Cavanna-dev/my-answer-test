
This code doesn't work as fast as we would like. Can you make it 3 times faster,
while having exactly the same output ? (in the same order).

Constraint: you can not use more than one core or processor (One easy solution
could be to use multiple processors. This is not what we want to test here.)

When rating the answer, we care mostly about what was changed in the code, 
rather than the execution speed of the program (as long as it runs in the
required time).

Note: The archive data.tar.gz must be extracted before starting the test: `tar xvzf data.tar.gz`.

The test can be done in either PHP, Go, or Javascript.

### Running

``` sh
# PHP:
$ time php test1.php ./words ./test1.in > test1.out
# Go:
$ go build test1.go && time ./test1 ./words < test1.in > test1.out
# NodeJS:
$ time node test1.js ./words < test1.in > test1.out
```

```
# Check output:
diff -u test1.exp test1.out && echo OK
```
