# email-by-php-for-splunk
Get email from an imap inbox for sending the emails into Splunk

This is just a simple method to get the email message from an inbox (in this case office 365) to a version that can be consumed into Splunk. You don't want to put binaries into splunk, so if you have emails that contain a binary image, etc., then you will have to make modifications to this simple script. 

The section where you decide what emails you want to get you will have to decide the method you want to use to get the emails. You can just get all the emails and then delete them, or you can just get those in the last hour, and leave them alone, or lots of other options. You should look at the IMAP spec to see what you want to do.

I guarantee nothing from this code. If it doesn't work for you, tough. If it messes up your Inbox, too bad. Don't come crying to me. I make not guarantees at all - USE AT YOUR OWN RISK!!!

Good luck.
