<?php
    Irc_Socket::noticeNick("Your access: " . Znc_User::getAccessFromHost(Irc_User::host(),Irc_User::Ident()));
?>