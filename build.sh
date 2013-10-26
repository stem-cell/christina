#!/bin/sh
php build-phar.php
dev="C:/xampp/myimouto/public/christina.phar"
if [ -f "$dev" ]; then
    cp -f release/christina.phar "$dev"
    echo "Phar copied to local XAMPP install."
fi
