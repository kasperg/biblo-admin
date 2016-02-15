#!/bin/sh

# Prepare the files directory for installation
if [ ! -d web/sites/default/files ]
  then
    mkdir web/sites/default/files
    chmod 777 web/sites/default/files
fi
