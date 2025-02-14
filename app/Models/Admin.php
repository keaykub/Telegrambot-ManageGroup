<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Log;

class Admin extends Authenticatable
{
    protected $table = 'admins_telegram'; // ชื่อตาราง
    protected $fillable = ['ADMIN_USERNAME', 'ADMIN_PASSWORD', 'ADMIN_ROLE'];

    // ระบุคอลัมน์ที่ใช้สำหรับ username
    public function getAuthIdentifierName()
    {
        return 'ADMIN_USERNAME';
    }

    public function getAuthPassword()
    {
        return $this->ADMIN_PASSWORD;
    }

    public function isAdmin()
    {
        return $this->ADMIN_ROLE === 'ADMIN' || $this->ADMIN_ROLE === 'TESTER';
    }

}
