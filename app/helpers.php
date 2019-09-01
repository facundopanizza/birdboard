<?php
    function gravatar_url($email)
    {
        $email = md5($email);
        return "https://gravatar.com/avatar/{$email}?" . http_build_query([
            's' => 60,
            'd' => 'http://www.web-soluces.net/webmaster/avatar/FaceCo-Homme.png'
        ]);
    }