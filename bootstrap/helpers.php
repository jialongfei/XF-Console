<?php

/** public notice style
 * @param $msg
 */
function error_notice($msg){
    die('<div style="color: #ff8e8e;text-align: center;margin-top: 200px;font-size: 26px;">'.$msg.'</div>');
}

/**
 * generate hash string password
 * @param $pwd
 * @return false|string
 */
function generate_password($pwd){
    return password_hash($pwd,PASSWORD_DEFAULT);
}
