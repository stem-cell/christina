<?php namespace Christina;

class Response
{
    // Shows the image that tells the user this post was blacklisted by him.
    function showBlacklistedImage()
    {
        // This is going to change in the future. Using a Phar archive means
        // this won't be so ugly anymore :) Wait and see.
        $png = 'iVBORw0KGgoAAAANSUhEUgAAAZAAAADIBAMAAAA0O6rRAAAABGdBTUEAALGP'
             . 'C/xhBQAAADBQTFRFzAAAyQAAzAAAwAAAzAAAzAAAzAAAwgAAzAAAzAAAzAAA'
             . 'zAAAxQAAAAAAvwAAzAAAjy0fgQAAAA50Uk5TVa2I7DMiZteZRBF3wgCWcqhN'
             . 'AAAMuUlEQVR42u2dX2wcRx3HK54QIYQXimiojNRSJJpiilAlGqyV4KGVqG2R'
             . 'B1SpdSxSVaEE5wQqUgu19gFoBGmyPEU6gjEBKUZKLiHkD3HPyUJCoRA75z+3'
             . '5/vjTYSgKKSJNvY5vnPuvD9ub3f+7c7u7Rl7c2fNvPjudzuz+5mZ3/zbma8f'
             . '0DdIeECACBABIkAEiAARIAJEgAgQASJABIgAWR1IakWACBABIkAEiAARIAJE'
             . 'gAgQASJAWgJk11Wofgp9SZ+S4cmz7PW5mq36ZsKVSv4Dl+HW1iFP6lYKtx5L'
             . 'RAoCF2rPcxCs8HnbkpHq3z5GX/6sUbeV+1i6jrrV/HTts0rlzDE7hXJ3pCAT'
             . 'un4RzCvJfQAP1XNThtLjyatgEaLwHEB1f3IbwH+YfE/VrowffgTgswxIwYAf'
             . 'vhLfJcNCIlqQI1AdsPLRKFk3jsH71p8PQ5k8GZg/tf4elWg6XYXt9RJ6Hkrd'
             . 'FEg+Zf6kzqnADyIESU3kZXNz/eNx68YZlI974QC6eM58GxEtkSR+BxUnx1+E'
             . 'f+vKILI/A+84JSaVElGCZHHGKfO1AjEHnMcw7mBfeAh9ipnYS/JyEfuAanan'
             . 'JpDdwEU5AoNRgqg432Zhc7qWuU7YYnrzs0CKKUtVnAwMYpARckneqEQI8g55'
             . 'IA265mAAfZtmHMIJ0l1SChRnZwWDKMS39F7oiw5knNQWXb0TWyCdhHHTGw//'
             . 'noZFyjxnyg5IDp4m5mlSOusPAlTx91cl6jGUJW+8kyX06Ex5aQAOyAxQ/WMe'
             . '7kUI0kWsU0BqVq1izHvjTYFToWIlxq4ikFiZNiuVCEEGaF82qWtmTJ3j7U6G'
             . 'y8tsSSEQ+S5t7i1FB0I/bRoWqG9Z8I4xck6NSqMHx9dO8OzXIBEZCFMV4A6T'
             . '+5t9QabpgqyFSQfAZc/CUGQgd/itK/XQTHAeeMbVyWiOfZYtggIviXUCWfQd'
             . '3efgCV+Q/nkfe9VVgA9HBrLiP01x+QFti1X49i0VbkG1Loi6HM5ON+6tApLb'
             . '89ZlQB2fZ6rs2DvuuswrrQaSHgM7OCC3+SApcIVWA3nBmr2O708mHZun1jkG'
             . 'qcVBflOb644mKJsfCGxPsuFCS4FMGvDRBPvAfiAT+v8V1hlEIWsqbQ2Shf/q'
             . 'GwIkRi0iODbZp/lt1rkjBdHoqSJqZu/5NL+tDMIMYR1bp0/PnlpuYRBmIMgf'
             . 'U9VGh84QZamFQdQ7DUe5tfG6A1huYZCOFa/NNe+ozeUdwGILg8iD3nG5e8Z0'
             . 'EgN2ty4I3TegKW3eNT5XMeAT7QGCFhlcCz15AzXVcLM9QE6gLzuZSfs08AFb'
             . 'C4Q24oW4AjMbH64ge7+ZaFkQZdFbhWpNALUEphmDfMDWAqH6hjlTQiA7qXZr'
             . 'xOxGIHm50rIg18jLAYW8PkiTFzppY4k4Uo9r6a6FQAr4DUoWDnTgyXoPvI4d'
             . 'Z4CApI2Flh3Gp5xhfFouUy890zL8qV6ZXoIv0k0bAdT13YmWAhmBp+oDQ6Xm'
             . 'Fuxr6H/+Pr6po/76lLzS0VLwV6f3PNh0NVtfkHwKvjAaPy/BX2qeT43Tj9nb'
             . 'CG5YLkS5fg2wmozH97wFUB1qrTm7sxniE1aPQU84cmMpGB+1r6XGXsdkZy3o'
             . 'e93r7iNNhvSYZL57NuCCHFOJtF1/A3N8/y8icPa1DjkYWpN07jtIARIbAyRr'
             . '6hsDZGZ+g4D0VzYIiLq4QUCkro0Bkmn27W2rgvzWTGwMkM418vX7DZJZxe7F'
             . 'lgSJmd3tCzLWR41PlvS2BdEMvJtZS5mb2xgkBfMv2/NABV7V2xekvp/8tXPx'
             . 'Q6cMeyLcvs6uob0QD+rtDVIrlPNX4dbWD+ltD7IOQYAIEAEiQASIABEgAkSA'
             . '3FeQOWgyTW+EppMIE9hdkAJEgEQCkj+87TKAOf5Yd3uDFPBOe/vQ89qD5M8l'
             . 'ogCZAxh/PJk8b532eH1dQHrWbIm0AUif/eHXjziH7dcapAMq0YDgj7sB7wZf'
             . 'SxCAasQguoorwVqCSFCOGoRsOlpLEBWWowbRh5HDrCXI9L8GIgfBmgAR9SMv'
             . 'rRdI3liJEqT5PjMsiK4sbxAQdECi7UF6yxsE5HpVgAiQ9fGRBa+Vr5ala7vG'
             . 'oXTpTFMg2vkUvHEm4JKvfRzMS992WzeNw5f39zUFElvyWPlqWfoOZx5zY8gd'
             . 'IS+XE7SF/KQp9SjbrZ9z1FnjCyjmafv7j5k75WxBr9JAMyDohDmx8tWy9OcB'
             . 'njwXPzRmQLXblYyjB8QBUeHG6M9P2SJjPJCLUKql+Zm6BJdO0VfPxQ//CIqb'
             . 'w4No6BgEtvqoZRWM4qP1D5OKHYUkUysQnQ+StcW4jkpWr6slk8nTYB+atgtV'
             . 'P27vbtafBVqhai88lbCt1URokCkYcln5aln5lImrw8UEmwwSaPKCqCW7ehYe'
             . '5taGtFx1qu9XqTllFleF5+DV0CCqZxjPVcvSnyFzST3vmqHL7j4VfUh7prws'
             . 'yAm8Yz4vE80apYRv21lMhATJBE2sKLUszXtYAkeY8gyg0Yc5j+wUO2CVyxQT'
             . 'KvBpKssyMBh2hhg41SV6LiPecwU4AlbM8oD0lwNb6CylSpUGdC/6LLauVMKB'
             . '/BL+EdQJEDWtjgXfvo08jgckthwIMlyktcTKaF5BH/CbhVAg34H3A3szrJY1'
             . 'yTlQjyIoJd0PpHMlEESiOU86m86zjCJROhgkXg9/SNF9Hg8Eq2XNmH1+IJTW'
             . 'nAckFQiSYfTOso6TuI4rK8ELdE5YSgSPL7BallrxHTYppEo3CTLLNAVpRyLQ'
             . 'JQHTGwrEfDAYBOs1STf9QGjxPw9Ix2IQSC+b98Y9u4NmK/FMYx9J18YAsD0U'
             . 'SIZ3VNiOoFJtjAfEW5D0XTrZnY72nLvgah6nQzn7C5J1IC1ggI3FEPp8QJiN'
             . 'sB6QXs++ZfouMqsLYVPPus5nTIZrfjMSzoAgkGslv6kF0+hzOsQD/iDuSjRc'
             . 'P5lx0nU+Ix2yQyxAOQTI8IIPCLszmTNEKfuDTLr2y9vN1XBllROrj6DkgkDU'
             . 'ZR8QpkA4g8YYKwvM3AU3iWiqWuTeKiwIVrh0gTBqWco9Pohrq7gXJANoKO29'
             . '95TL8a7Xf+pYWe1UVy3x8FxqWSt8kBit58mdWO2F4ts+955zK1QB91ahQZDM'
             . 'Km11q2VJg1yQtCFVG4BoKVtJmnPvGS6INLFaECS9Qlkbq2XZEXrKs0wHwwHR'
             . 'cylGPpv65XrRJbWV5N4qNAhqzok1hFqWHcE4kDeWGoBYR5HhZ1yQauPVn6ZA'
             . 'HBUcYg2hlmVHKFldXncDEF27CGST/3qCOC03tWDQWC3LjlCXyH66EYie7yST'
             . '//UEcfpS0vZ71bKk21wQ6zql2hDEOqLPGT9c5y46Gl2rBnFyBlm5alkrvquf'
             . 's9QoxA9EP4LlmJnmlwey+ubXDcJTy1IWfUFod/cF0eUqD2SIk6pn5L9qEJ5a'
             . 'FlcVy4nQS2SN/EF2oouYIQrvjan7Vlp4kP4Fdg7BUcsaLvuDZIgakD8I1j5i'
             . 'Bo08ZW/3rXKrbrV4alnXzIA3BAGLD958ZYbxXZxUTxbdWRAaxJGLQ1aeWtYU'
             . 'rxagCCP+y0FUY3SbM7Hiex570m82PIjTtiIrTy2LtxpENXNLjUFQY8QsDvJm'
             . 'OQVXhetvYqx1wBcEq2VJy/4g+rDHk8OBcIWd3FLynaFBZp1WkAdygkhKBoAU'
             . 'kLsHgXR5f8lyj1QrZda5ws9Hqv5rUSrR37/gD4LdPQCEqFL1UQ/JUz/rZ5xk'
             . 'CqSQIBl4z9UI8dSycrAYAOL/fsQzP2fmt+o810noWeeWhVTY9yOoQUJWvlpW'
             . 'p/fYMDV7MpYagOD3C4yy+Qi3blHvGvS0cbMzHMjXwd3k8NWy5sj/HeEk47i7'
             . 'P0isin25i35M3oihh2q3emBIDQWywzDd41K+WlZeNi/oAQsiN7kgCfzE72Gv'
             . 'X2KembOCmTbKKF6uRqoONgbRvgno/+VQVr5a1hFAr/t0bV/Cne32SrYbJIcW'
             . '+1XSocboNjdtlLDLZLBr/Aq+5OSeFU9dCQC5dC4ej+8ZMwD+7sXzUcu6CPP2'
             . 'PO9oyq7aNIjt7m6QOSjXo+wGeoT8fepRXrT/IVMtfJBUM6wwdtpa0A0EQaF6'
             . 'llNOPmpZ1qv/K6/EN+1zBLMYEK3+33bcIBkJzK3xwwehSJqqvAxvxve8RrIH'
             . 'rozGD51P0Xl6DOoKY6n6u+0gkDfGLYGr8Suj/Crvo5alfQ6dhk54PXqL1ax6'
             . 'fCRjC2kV6ZHa8fr7DMz1RyfR0qPUNTsMsl9CvecL0jD4qWUd/qQE4/uHmkhJ'
             . '+9ZlqG5lW+6vXIXvUol/Y5sMt94dZUcrlsLYn89wEhRHkwSIABEgAkSACBAB'
             . 'IkAEiAARIAJEgAgQASJABEg7hv8B604+HFJgSVYAAAAASUVORK5CYII=';
        header('Content-Type: image/png');
        echo base64_decode($png);
        Response::someThingsNeverChange(); // like people's minds about blacklists.
    }

    // Tells the browser not to bother making a request next time as the content
    // will stay the same. A basic kind of optimization.
    function someThingsNeverChange()
    {
        // Note that HTTP 1.1 states that the date shouldn't be more than one year
        // into the future. See section 14.21 ("Expires") of RFC 2616 (HTTP 1.1),
        // specifically the first paragraph of page 127.
        $secondsInYear = 60 * 60 * 24 * 365;
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + $secondsInYear)); 
    }

    // I don't blame you 'cause most of the time I don't, either :)
    function youProbablyDontKnowWhatYouAreDoing()
    {
        echo nl2br("HypnoHub Hover Zoom ".CHRISTINA_VERSION." API endpoint"
                  ." - please provide a valid query, my young grasshopper. \n\n");
        echo nl2br("This is what you gave us on <code>\$_GET</code>, see? No valid query data:\n");
        echo '<pre>';
        var_dump($_GET);
        echo '</pre>';
    }
}