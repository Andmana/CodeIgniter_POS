<?php

use App\Models\AuthModel;

function allow($level)
{
    $session = \Config\Services::session();
    $user = $session->get('username');
    $table = 'users';
    $auth = new AuthModel();
    $row = $auth->get_data_login($user, $table);
    if ($row != NULL) {
        if ($row->level > $level) {
            return true;
        } else {
            return false;
        }
    }
}
