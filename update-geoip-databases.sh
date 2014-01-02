#!/bin/bash
# This script updates the GeoIP databases for us. Disclaimer: "This product
# includes GeoLite data created by MaxMind, available from http://maxmind.com."

# URLs from http://dev.maxmind.com/geoip/legacy/geolite/
URLs=$(cat <<HEREDOC
http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz
http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz
http://geolite.maxmind.com/download/geoip/database/GeoLiteCityv6-beta/GeoLiteCityv6.dat.gz
http://download.maxmind.com/download/geoip/database/asnum/GeoIPASNum.dat.gz
http://download.maxmind.com/download/geoip/database/asnum/GeoIPASNumv6.dat.gz
HEREDOC
)

# Prints $1 copies of $2 character. No trailing newlines.
repeat() {
    printf %${1}s | tr " " "$2"
}

echo "Go grab a cup of coffee, this might take a moment." # It's called "being honest".

for url in $URLs
do
    name="$(basename ${url%.*})"
    # I'm cleaning the lines with backspace characters because I'm on Windows.
    # What the following does is to go back to the line start, fill the line buffer
    # with whitespace, and go back again so we can write the next line.
    repeat ${#line} $'\b'
    repeat ${#line} " "
    repeat ${#line} $'\b'
    line="Downloading $name..."
    echo -n "$line"
    # Fun fact: in the next command, the -dfc stands for "Delicious Flat Chest".
    wget "$url" -qO- | gzip -dfc - > libs/maxmind/databases/$name
done;

repeat ${#line} $'\b'
repeat ${#line} " "
repeat ${#line} $'\b'
echo -e "All databases downloaded."
