/* 
   this is here, because the neopixel can't run as non root user
   it needs access to /dev/mem - i found that wrapper the most elegant
   method that i can control the light in case a project ends when 
   the light is off - a last picture and timelapse is made.
   for added security, i don't want to pass any variables to this
   wrapper, it's solely purpose is to run this one script.
*/   

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>

int main() {
	setuid(0);
	system("/home/fox/growbox/backend/light -s");
	return 0;
}
