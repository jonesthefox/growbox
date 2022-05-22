<?php

$passwd =  str_replace('/scripts/setup/includes', '/.passwd', __DIR__);
echo "\n";
echo "Let us create a user now.";
echo "\n";
$user = readline("Username: ");

echo "We generate a password now. Press 'y' when you are statisfied, any other key to generate a new one";
echo "\n";

$pass = '';

while (true)
{
    for ($i = 0; $i < 8; $i++ )
    {
        try { $pass .= chr(random_int(33,125)); }
        catch (Exception $e) { die($e->getMessage()); }
    }

    echo "\n$pass\n\n";

    $ok = readline("Okay? (Y/N) ");

    if ($ok == 'Y' || $ok == 'y') { break; }
    else { $pass = ''; }
}

file_put_contents($passwd,json_encode(array(
    'user' => $user,
    'pass' => password_hash($pass, PASSWORD_DEFAULT))));

echo "\nWrote login credentials to $passwd\n\n";
echo "\nPlease remember your password now, the screen will be wiped!\n\n";
readline('Press ENTER to continue...');
system('clear');

echo "\ndone!\n";
