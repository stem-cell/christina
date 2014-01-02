<?php namespace Christina;

// This class handles understanding user-agent capabilities and properties.
// It has static elements but it also represents instances.
// Remember that you should not really trust the information that the user-agent sends.
class Agent
{
    // Path to use as directory cache for phpbrowsecap.
    // Must be relative to the top-level christina.php script.
    const cacheDirectory = '../tmp';

    // Indicates whether the user-agent detection code has been initialized.
    private static $initialized = false;

    // User agent string representing this instance.
    public $userAgent = '';

    // Platform short name, description, and version.
    public $platform            = ''; // e.g., "Win7".
    public $platformDescription = ''; // e.g., "Windows 7".
    public $platformVersion     = ''; // e.g., "6.1".

    // Architecture. currently only "Win32", "Win64" and "Win16" (does that even exist anymore?).
    public $architecture = '';

    // User-agent name, e.g., "Chrome".
    public $name = '';

    // Version, e.g., "30.0". Also parsed into major and minor (e.g., 30 and 0, as ints).
    public $version      = '';
    public $majorVersion = 0;
    public $minorVersion = 0;

    // Build type, if known. May also be "alpha" or "beta".
    public $build = 'stable or unknown';

    // Rendering engine. Note that this value doesn't seem to be too reliable, or
    // at least it's very naive - Chrome and Opera now use Blink, but it will report
    // "Webkit", and who knows if it'll report "Servo" when Mozilla phases-out Gecko.
    public $engine = '';

    // Is it a mobile device, crawler or syndication reader?
    public $mobile            = false;
    public $crawler           = false;
    public $syndicationReader = false;

    // Device name and maker, if they can be determined.
    public $deviceName  = '';
    public $deviceMaker = '';

    // IP address of the user-agent, only available through ::current().
    // Also, remember this is still not 100% reliable. Nothing in this class is.
    public $ip = null;

    // Autonomous System Number and ISP or organization name.
    public $asn = 'unknown';
    public $isp = 'unknown';

    // Where the user is expected to be, as a Location object.
    public $location = null;

    // Constructs an Agent instance with informations from a given user agent string.
    function __construct($userAgent)
    {
        $this->userAgent = $userAgent;

        $raw = Agent::getRaw($userAgent);

        $this->platform            = $raw['Platform'];
        $this->platformDescription = $raw['Platform_Description'];
        $this->platformVersion     = $raw['Platform_Version'];

        foreach (['Win16', 'Win32', 'Win64'] as $arch)
        {
            if ($raw[$arch]) $this->architecture = $arch;
        }

        $this->name = $raw['Browser'];

        $this->version      = $raw['Version'];
        $this->majorVersion = $raw['MajorVer'];
        $this->minorVersion = $raw['MinorVer'];

        foreach (['Beta', 'Alpha'] as $build)
        {
            if ($raw[$build]) $this->build = strtolower($build);
        }

        $this->engine = $raw['RenderingEngine_Name'];

        $this->mobile            = $raw['isMobileDevice'];
        $this->crawler           = $raw['Crawler'];
        $this->syndicationReader = $raw['isSyndicationReader'];

        $this->deviceName  = $raw['Device_Name'];
        $this->deviceMaker = $raw['Device_Maker'];
    }

    // Gets information for the current user-agent. Besides from the basic
    // user-agent information, also retrieves extra properties like IP info.
    static function current()
    {
        $userAgent = Agent::userAgent();
        $agent = new Agent($userAgent);
        $agent->ip = $ip = $_SERVER['REMOTE_ADDR'];

        if ($geo = Agent::geoIpData($ip))
        {
            if ($i = $geo['asName']) $agent->isp = $i;
            if ($i = $geo['asCode']) $agent->asn = $i;
            $agent->location = new Location($geo);
        }

        return $agent;
    }

    // Gets the raw data for the user agent as an array.
    private static function getRaw($userAgent)
    {
        Agent::init();

        // We'll use a hash of the user agent as part of the caching key.
        $hash = md5($userAgent);
        $key = "user-agent-$hash";

        return Cache::lifecycle($key, function() use ($userAgent)
        {
            $bc = new \phpbrowscap\Browscap(Agent::cacheDir());
            return $bc->getBrowser($userAgent, true);
        });
    }

    // Returns the cache directory (to be) used by phpbrowsecap.
    static function cacheDir()
    {
        return normalizePath(CHRISTINA_ROOT.'/'.Agent::cacheDirectory);
    }

    // Returns the user agent reported by the browser.
    // If none was reported, returns an empty string.
    static function userAgent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            return $_SERVER['HTTP_USER_AGENT'];
        }
        else
        {
            return '';
        }
    }

    // Gets GeoIP data for a given IP. Currently uses MaxMind's GeoIP2 databases
    // whose licenses and downloads you can find here: http://dev.maxmind.com/geoip/
    // If this function has any problem it will return null.
    static function geoIpData($ip)
    {
        Agent::init();

        // We'll use a hash of the user agent as part of the caching key.
        $hash = md5(strtolower($ip));
        $key = "geo-ip-data-$hash";

        try
        {
            return Cache::lifecycle($key, function() use ($ip)
            {
                // Check if the IP is valid and if it's an IPv4 or IPv6.
                if (!filter_var($ip, FILTER_VALIDATE_IP)) return null;
                $isIpv4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

                $v = $isIpv4 ? '' : 'v6';
                $flags = GEOIP_STANDARD;

                $ipDatabasePath   = libPath("maxmind/databases/GeoIP$v.dat"); // Not used.
                $cityDatabasePath = libPath("maxmind/databases/GeoLiteCity$v.dat");
                $asnDatabasePath  = libPath("maxmind/databases/GeoIPASNum$v.dat");

                $cityDatabase = geoip_open($cityDatabasePath, $flags);
                $asnDatabase  = geoip_open($asnDatabasePath , $flags);

                if ($isIpv4)
                {
                    $asn = geoip_name_by_addr($asnDatabase, $ip);
                    $record = geoip_record_by_addr($cityDatabase, $ip);
                }
                else
                {
                    $asn = geoip_name_by_addr_v6($asnDatabase, $ip);
                    $record = geoip_record_by_addr_v6($cityDatabase, $ip);
                }

                if (!$record) return null;

                preg_match('/^((?<code>AS\d+) )?(?<name>.*)$/i', $asn, $autonomousSystem);

                $result = [
                    'countryName'   => $record->country_name,
                    'countryCode'   => $record->country_code,
                    'region'        => $record->region,
                    'city'          => $record->city,
                    'latitude'      => $record->latitude,
                    'longitude'     => $record->longitude,
                    'continentCode' => $record->continent_code,
                    'asName'        => $autonomousSystem['name'],
                    'asCode'        => $autonomousSystem['code']
                ];

                geoip_close($cityDatabase);
                geoip_close($asnDatabase);

                return $result;
            });
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    // Loads the PHP browser capabilities library, if that hasn't been done already.
    static function init()
    {
        if (!Agent::$initialized)
        {
            lib('phpbrowscap/browscap');
            lib('maxmind/geoip');
            Agent::$initialized = true;
        }
    }
}
