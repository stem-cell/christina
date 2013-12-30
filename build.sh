#!/bin/sh

# Compile LESS into CSS and minify.
echo -n Compiling and minifying CSS
for less in less/*.less
do
    file=${less##*/}
    name=${file%.*}
    lessc --no-color "$less" "css/$name.css"
    lessc --no-color --clean-css "$less" "css/$name.min.css" 1> NUL 2> NUL
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

# Build the readme.
codeSize="$(./code-size.sh | sed 's/^/              /')"
pharSize=$(du -h release/christina.phar | cut -f1)
awk -v r="$codeSize" '{gsub(/{{CODE_SIZE}}/,r)}1' doc/templates/README.md \
| awk -v r="$pharSize" '{gsub(/{{PHAR_SIZE}}/,r)}1' > README.md
