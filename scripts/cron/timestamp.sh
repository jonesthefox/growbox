#!/bin/bash
while read -r x; do
    echo -n "$(date +%Y-%m-%d\ %H:%M:%S)";
    echo -n " ";
    echo "$x";
done
