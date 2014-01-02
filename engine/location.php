<?php namespace Christina;

// Physical location abstraction class.
class Location
{
    // Country name, in English.
    public $countryName = '';

    // Country code, as two letters in ISO 3166-1 format. See:
    // http://en.wikipedia.org/wiki/ISO_3166-1
    // In addition, the following may be issued:
    // A1 - an anonymous proxy.
    // A2 - a satellite provider.
    // EU - an IP in a block used by multiple European countries.
    // AP - an IP in a block used by multiple Asia/Pacific region countries.
    // US - an IP in a block associated with overseas US military bases.
    public $countryCode = '';

    // Latitude, as a float.
    public $latitude = 0.0;

    // Longitude, as a float.
    public $longitude = 0.0;

    // Continent code, as two letters. Possible values are:
    // AF - Africa
    // AS - Asia
    // EU - Europe
    // NA - North America
    // OC - Oceania
    // SA - South America
    public $continentCode = '';

    // Region code, as two letters. May be unavailable.
    // For the US and Canada, it should be an ISO-3166-2 code. For other countries, it
    // will be FIPS 10-4. In addition the values AA, AE, and AP may be specified for the
    // US armed forces in America, Europe and Pacific, respectively.
    // This CVS can be used to map region codes to region names:
    // http://dev.maxmind.com/static/csv/codes/maxmind/region.csv
    // See also:
    // http://en.wikipedia.org/wiki/ISO_3166-2
    // http://en.wikipedia.org/wiki/FIPS_10-4
    public $region = null;

    // City name, in English. May be unavailable.
    // Possible values are defined here: http://www.maxmind.com/GeoIPCity-534-Location.csv
    public $city = null;

    // Build a location from an array compatible with the one returned by Agent::geoIpData().
    // We're looking for the properties "countryName", "countryCode", "latitude", "longitude",
    // "continentCode", and optionally "region" and "city". The format expected on these is
    // documented on this page: http://dev.maxmind.com/geoip/legacy/web-services/
    // This uses the same convention as MaxMind's geolocation databases (that's the purpose).
    function __construct($geoLocation)
    {
        $this->countryName   = $geoLocation['countryName'];
        $this->countryCode   = $geoLocation['countryCode'];
        $this->latitude      = floatval($geoLocation['latitude']);
        $this->longitude     = floatval($geoLocation['longitude']);
        $this->continentCode = $geoLocation['continentCode'];
        $this->region        = $geoLocation['region'];
        $this->city          = $geoLocation['city'];
    }
}
