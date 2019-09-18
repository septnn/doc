#include <stdio.h>
#include "t1.c"

int main() {
    printf("hello world\n");
    printf("%s: %d\n", __FILE__, __LINE__);
#line 333 "t1.c"
    printf("%s: %d\n", __FILE__, __LINE__);
    t();
    printf("%s: %d\n", __FILE__, __LINE__);
    return 0;
}
