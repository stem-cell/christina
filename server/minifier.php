<?php namespace Christina;

// Minifies output HTML by regular expressions. Cool until Cthulhu gets ya:
// http://stackoverflow.com/a/1732454/124119
// Also, the one who tamed this madness was this guy:
// http://stackoverflow.com/a/5324014/124119
function minify($html)
{
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
    $result = preg_replace($re, " ", $html);
    if ($result === null) return $html; // Couldn't handle it.
    return $result;
}
