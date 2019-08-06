<?php
// 3 m
// 2 w
// 1 z

echo (1 | 2);
echo PHP_EOL; // z+w
echo (1 | 2) & 1;
echo PHP_EOL; // z+w z
echo (1 | 2) & 2;
echo PHP_EOL; // z+w w
echo (1 | 2) & 3;
echo PHP_EOL; // z+w z+w
echo (1 | 2) & 4;
echo PHP_EOL; // z+w m
echo (1 | 2) & 5;
echo PHP_EOL; // z+w m
echo decbin(5);
echo PHP_EOL; // 101=m+z
echo '---------------';
echo (1 | 4);
echo PHP_EOL; // z+m
echo (1 | 4) & 1;
echo PHP_EOL; // z+m z
echo (1 | 4) & 2;
echo PHP_EOL; // z+m w
echo (1 | 4) & 3;
echo PHP_EOL; // z+m z+w
echo (1 | 4) & 4;
echo PHP_EOL; // z+m m
echo (1 | 4) & 5;
echo PHP_EOL; // z+m m
echo decbin(5);
echo PHP_EOL; // 101=m+z
echo '---------------';
echo (1 | 4 | 2);
echo PHP_EOL; // z+m+w
echo (1 | 4 | 2) & 1;
echo PHP_EOL; // z+m+w z
echo (1 | 4 | 2) & 2;
echo PHP_EOL; // z+m+w w
echo (1 | 4 | 2) & 3;
echo PHP_EOL; // z+m+w z+w
echo (1 | 4 | 2) & 4;
echo PHP_EOL; // z+m+w m
echo (1 | 4 | 2) & 5;
echo PHP_EOL; // z+m+w m
echo decbin(5);
echo PHP_EOL; // 101=m+z
echo '---------------';
echo (4 | 2);
echo PHP_EOL; // m+w
echo (4 | 2) & 1;
echo PHP_EOL; // m+w z
echo (4 | 2) & 2;
echo PHP_EOL; // m+w w
echo (4 | 2) & 3;
echo PHP_EOL; // m+w z+w
echo (4 | 2) & 4;
echo PHP_EOL; // m+w m
echo (4 | 2) & 5;
echo PHP_EOL; // m+w m
echo decbin(5);
echo PHP_EOL; // 101=m+z
