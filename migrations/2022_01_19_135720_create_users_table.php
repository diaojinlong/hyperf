<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->char('tel', 11)->default('')->comment('手机号');
            $table->char('pwd', 32)->default('')->comment('密码');
            $table->char('salt', 4)->default('')->comment('密码盐');
            $table->tinyInteger('status')->default(1)->unsigned()->comment('状态:1=正常,2=禁用');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
