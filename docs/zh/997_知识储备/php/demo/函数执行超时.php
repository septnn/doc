<?php
declare(ticks = 1);
function a(){
    sleep(10);
    echo "a 超时\n";
}
function b(){
    echo "b\n";
}
function c(){
    echo "超时".PHP_EOL;
    usleep(30000);
}
 
function sig(){
    throw new Exception;
}
 
try{
    pcntl_alarm(1);
    pcntl_signal(SIGALRM, "sig");
    a();
    pcntl_alarm(0);
}catch(Exception $e){
    echo "timeout\n";
}
// b();
// a();
// b();