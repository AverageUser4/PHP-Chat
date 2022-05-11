<?php


echo 'a';
sleep(1);
ob_end_clean();
flush();
echo 'b';
sleep(1);
echo 'c';
sleep(1);
echo 'd';
sleep(1);
echo 'e';
sleep(1);


//echo var_dump(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES));