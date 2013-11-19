<?php namespace Christina;

// Helper for the dashboard page.
class Dashboard
{
    // Links that can be accessed by any guest.
    static $guest = [
        ['Feature Support Information', 'features'],
        ['Raw PHP Information', 'phpinfo']
    ];

    // Returns a list of links (containing name, url and type) for the dashboard.
    static function links()
    {
        $links = [];
        $types = [
            'guest' => Dashboard::$guest
        ];

        foreach ($types as $type => $list)
        {
            foreach ($list as $link)
            {
                $name = $link[0];
                $url = $link[1];
                $nameRe = '/^[a-z_]+[a-z0-9_-]+$/i';
                if (preg_match($nameRe, $url)) $url = Routes::url($url);

                $links[] =
                [
                    'name' => $name,
                    'url' => $url,
                    'type' => $type
                ];
            }
        }

        return $links;
    }
}