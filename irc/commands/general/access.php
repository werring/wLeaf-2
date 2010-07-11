<?php
/**
 * Returns your access level
 * @todo make it posible to requests someone else his access
*/  
    Irc_Socket::noticeNick("Your access: " . Znc_User::getAccessFromHost(Irc_User::host(),Irc_User::Ident()));
?>