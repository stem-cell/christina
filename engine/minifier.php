<?php namespace Christina;

class Minify
{
    // Minifies output HTML by regular expressions. Cool until Cthulhu gets ya:
    // http://stackoverflow.com/a/1732454/124119
    // Don't do this at home, kids!
    static function html($html)
    {
        // First, wipe out the HTML comments.
        $html = preg_replace('/<!--(.*?)-->/', '', $html);

        // The one who tamed the madness that follows was this guy:
        // http://stackoverflow.com/a/5324014/124119
        $re = '%          # Collapse whitespace everywhere but in blacklisted elements.
            (?>           # Match all whitespans other than single space.
              [^\S ]\s*   # Either one [\t\r\n\f\v] and zero or more ws,
            | \s{2,}      # or two or more consecutive-any-whitespace.
            )             # Note: The remaining regex consumes no text at all...
            (?=           # Ensure we are not in a blacklist tag.
              [^<]*+      # Either zero or more non-"<" {normal*}
              (?:         # Begin {(special normal*)*} construct
                <         # or a < starting a non-blacklist tag.
                (?!/?(?:textarea|pre|script)\b)
                [^<]*+    # more non-"<" {normal*}
              )*+         # Finish "unrolling-the-loop"
              (?:         # Begin alternation group.
                <         # Either a blacklist start tag.
                (?>textarea|pre|script)\b
              | \z        # or end of file.
              )           # End alternation group.
            )             # If we made it here, we are not in a blacklist tag.
            %Six';
        $basic = preg_replace($re, " ", $html);

        if ($basic === null) return $html; // Couldn't handle it.

        $parts = preg_split('~</?body~i', $basic);

        if (sizeof($parts) !== 3)
        {
            return $basic;
        }

        $head = trim(str_replace('> <', '><', $parts[0]));
        $body = '<body'.rtrim(str_replace_first('> <', '><', $parts[1])).'</body';
        $tail = rtrim(str_replace('> <', '><', $parts[2]));

        return $head.$body.$tail;
    }
}
