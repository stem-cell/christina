<?php namespace Christina;

echo '<table>';

foreach ($rows as $row)
{
    echo '<tr>';

    foreach ($row as $item)
    {
        echo "<td>$item</td>";
    }

    echo '</tr>';
}

echo '</table>';
