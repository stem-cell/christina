#!/bin/sh
cloc $(find engine errors less parsers routes rules sql templates *.php *.sh -path "*.*") | tail -n +6
