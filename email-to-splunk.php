<?php

$now = time();
//Go back 5 mintes to the beginning of the minute
$earliest = $now-300-($now%60);
$yesterday = strftime('%d %B %Y', $now-3600*24);

$username = 'user@domain.org';
$password = 'userpassword';
$hostname = '{mail.domain.org:993/imap/ssl/novalidate-cert}INBOX';

while (!($inbox = imap_open($hostname,$username,$password,NULL,1,array('DISABLE_AUTHENTICATOR' => 'GSSAPI'))))
{
	sleep (1);
}
// Get the RECENT emails
// This is a section that you have to decide how you want to get the emails:
//$emails = imap_search($inbox,'RECENT');
//$emails = imap_search($inbox,'ALL');
$emails = imap_search($inbox,"SINCE \"{$yesterday}\"");

if($emails) {
    $output = '';
    sort($emails);

    foreach($emails as $email_number) {
        $overview = imap_fetch_overview($inbox,$email_number,0);
        $structure = imap_fetchstructure($inbox, $email_number);

        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
	}
        $header = imap_fetchbody($inbox,$email_number,0);
	if (isset($structure->parts[1])) {
            $body = imap_fetchbody($inbox,$email_number,1);
	} else {
            $body = base64_decode (imap_fetchbody($inbox,$email_number,1));
	}
        $message = imap_fetchbody($inbox,$email_number,2);

	preg_match("/^From: .*$/m", $header, $from);
	preg_match("/^To: .*$/m", $header, $to);
	preg_match("/^Subject: .*$/m", $header, $subject);
	preg_match("/^Date: (.*$)/m", $header, $date);
	$time = strtotime($date[1]);
	$dateInLocal = date("Y-m-d H:i:s", $time);
	if ($time < $earliest) continue;
	preg_match("/^Message-ID: .*/m", $header, $mess_id);

	$output .= ">>>Start<<<\nDate: {$dateInLocal}\n{$subject[0]}\n{$to[0]}\n{$from[0]}\n{$mess_id[0]}\n\nBODY:\n{$body}\n_______________________\n\n";

        if(isset($part) && $part->encoding == 3) {
            $message = imap_base64($message);
        } else if(isset($part) && $part->encoding == 1) {
            $message = imap_8bit($message);
        } else {
            $message = imap_qprint($message);
        }
	$message = preg_replace('/[[:^print:]]/', '', $message);
	$output .= "{$message}\n>>>END<<<\n\n";
    }
    echo $output;
}

imap_close($inbox);

?>
