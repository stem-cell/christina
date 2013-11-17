#!/bin/sh

# Compile LESS into CSS and minify.
echo -n Compiling and minifying CSS
for less in less/*.less
do
    file=${less##*/}
    name=${file%.*}
    lessc "$less" "css/$name.css"
    lessc --clean-css "$less" "css/$name.min.css"
    echo -n .
done
echo " Done."

# Build the Phar archive.
php build-phar.php

# Copy it to the development server, if any.
dev="C:/xampp/myimouto/public/christina.phar"
if [ -f "$dev" ]; then
    cp -f release/christina.phar "$dev"
    echo "Phar copied to local XAMPP install."
fi
