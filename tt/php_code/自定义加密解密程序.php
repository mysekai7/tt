<?php
$key = "This is supposed to be a secret key !!!";

function keyED($txt,$encrypt_key)
{
$encrypt_key = md5($encrypt_key);
$ctr=0;
$tmp = "";
for ($i=0;$i<strlen($txt);$i++)
{
if ($ctr==strlen($encrypt_key)) $ctr=0;
$tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
$ctr++;
}
return $tmp;
}

function encrypt($txt,$key)
{
srand((double)microtime()*1000000);
$encrypt_key = md5(rand(0,32000));
$ctr=0;
$tmp = "";
for ($i=0;$i<strlen($txt);$i++)
{
if ($ctr==strlen($encrypt_key)) $ctr=0;
$tmp.= substr($encrypt_key,$ctr,1) .
(substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
$ctr++;
}
return keyED($tmp,$key);
}

function decrypt($txt,$key)
{
$txt = keyED($txt,$key);
$tmp = "";
for ($i=0;$i<strlen($txt);$i++)
{
$md5 = substr($txt,$i,1);
$i++;
$tmp.= (substr($txt,$i,1) ^ $md5);
}
return $tmp;
}

$string = "Hello World !!!";

// encrypt $string, and store it in $enc_text
$enc_text = encrypt($string,$key);

// decrypt the encrypted text $enc_text, and store it in $dec_text
$dec_text = decrypt($enc_text,$key);

print "Original text : $string <Br>\n";
print "Encrypted text : $enc_text <Br>\n";
print "Decrypted text : $dec_text <Br>\n";
?>

