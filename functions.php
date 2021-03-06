<?php
function links()
{
    echo file_get_contents("static/links.html");
}

function element($url, $name, $icon)
{
    echo '<a href="' . $url . '"><div class="box small-width small-height"><i class="' . $icon . '"></i><p>' . $name . '</p></div></a>';
}
function elementOnClick($onclick, $name, $icon)
{
    echo '<a onclick="' . $onclick . '"><div class="box small-width small-height"><i class="' . $icon . '"></i><p>' . $name . '</p></div></a>';
}
function elementCustomClass($url, $name, $icon, $class)
{
    echo '<a href="' . $url . '"><div class="box small-width small-height ' . $class . '"><i class="' . $icon . '"></i><p>' . $name . '</p></div></a>';
}