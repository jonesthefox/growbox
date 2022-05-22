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

int main(int argc, char ** const argv) {
        char command[256];
        char cmd[] = "/home/fox/growbox/backend/light.py -c ";
        strcpy( command, cmd);
        strcpy( command+strlen(command), argv[1]);
        strcpy( command+strlen(command), " ");
        strcpy( command+strlen(command), argv[2]);
        strcpy( command+strlen(command), " ");
        strcpy( command+strlen(command), argv[3]);
        strcpy( command+strlen(command), " ");
        strcpy( command+strlen(command), argv[4]);
	setuid(0);
	system(command);
	return 0;
}
