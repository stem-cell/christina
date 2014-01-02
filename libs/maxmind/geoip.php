<?php

// This file loads all necessary files from MaxMind GeoIP component version 1.14.
// More info at: https://github.com/maxmind/geoip-api-php
require_once __DIR__.'/geoip/timezone.php';
require_once __DIR__.'/geoip/geoipregionvars.php';
require_once __DIR__.'/geoip/geoip.php';
require_once __DIR__.'/geoip/geoipcity.php';

// By the way, whenever you want to use GeoIP2 (https://github.com/maxmind/GeoIP2-php)
// you have to include the following:
/*
require_once __DIR__.'/geoip2/Dependencies/MaxMind/Db/Reader/InvalidDatabaseException.php';
require_once __DIR__.'/geoip2/Dependencies/MaxMind/Db/Reader/Decoder.php';
require_once __DIR__.'/geoip2/Dependencies/MaxMind/Db/Reader/Metadata.php';
require_once __DIR__.'/geoip2/Dependencies/MaxMind/Db/Reader.php';
require_once __DIR__.'/geoip2/ProviderInterface.php';
require_once __DIR__.'/geoip2/Exception/GeoIp2Exception.php';
require_once __DIR__.'/geoip2/Exception/AddressNotFoundException.php';
require_once __DIR__.'/geoip2/Exception/AuthenticationException.php';
require_once __DIR__.'/geoip2/Exception/OutOfQueriesException.php';
require_once __DIR__.'/geoip2/Exception/HttpException.php';
require_once __DIR__.'/geoip2/Exception/InvalidRequestException.php';
require_once __DIR__.'/geoip2/Model/Country.php';
require_once __DIR__.'/geoip2/Model/City.php';
require_once __DIR__.'/geoip2/Model/CityIspOrg.php';
require_once __DIR__.'/geoip2/Model/Omni.php';
require_once __DIR__.'/geoip2/Record/AbstractRecord.php';
require_once __DIR__.'/geoip2/Record/AbstractPlaceRecord.php';
require_once __DIR__.'/geoip2/Record/City.php';
require_once __DIR__.'/geoip2/Record/Continent.php';
require_once __DIR__.'/geoip2/Record/Country.php';
require_once __DIR__.'/geoip2/Record/Location.php';
require_once __DIR__.'/geoip2/Record/MaxMind.php';
require_once __DIR__.'/geoip2/Record/Postal.php';
require_once __DIR__.'/geoip2/Record/RepresentedCountry.php';
require_once __DIR__.'/geoip2/Record/Subdivision.php';
require_once __DIR__.'/geoip2/Record/Traits.php';
require_once __DIR__.'/geoip2/Database/Reader.php';
*/
// The reason I'm not using GeoIP2 is because it's really not ready yet. It can seemingly
// detect cities, but not Autonomous Systems, and it doesn't support IPv6 yet.
//
// As a side note, all of this depends on the BCMath PHP extension. You likely have it anyway.
