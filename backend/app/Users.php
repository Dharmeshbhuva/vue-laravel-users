<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $fillable = ['full_name', 'email', 'authorized_on', 'user_type_id'];
	
	public static function fetchAllUsers() {
		return \DB::select("SELECT u.id, u.full_name, u.email, ull.login_time, u.authorized_on, ut.name AS user_type_name FROM users u
		JOIN user_types ut ON u.user_type_id = ut.id
		LEFT JOIN user_login_logs ull ON u.id = ull.user_id
        WHERE
		ull.id IS NULL OR ull.id IN (
			SELECT id
			FROM user_login_logs
			WHERE user_login_logs.user_id = u.id
			ORDER BY login_time DESC
		)
		ORDER BY u.id ASC");
	}
}
