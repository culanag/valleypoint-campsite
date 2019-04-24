<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();            
            $table->string('name');
            //$table->string('firstName');
            $table->enum('role',['admin', 'general', 'lodging', 'cashier']);
            $table->string('contactNumber')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        /*DB::table('users')->insert(
            array(
                'username' => 'lodging1',
                'password' => 'lodging1',
                'lastName' => 'Aquino',
                'firstName' => 'Jhaypee',
                'role' => 'lodging',
                'contactNumber' => '09000000000',
                'email' => 'jhaypee@valleypoint.com'
            )
        );*/
        $user = new User;
        $user->username = 'jpaquino';
        $user->password = Hash::make('jpaquino');
        $user->name = 'JP Aquino';
        $user->role = 'lodging';
        $user->contactNumber = '09178504634';
        $user->email = 'jpaquino@valleypoint.com';
        $user->save();

        $user = new User;
        $user->username = 'jhaypee';
        $user->password = Hash::make('jhaypee');
        $user->name = 'Jhaypee';
        $user->role = 'admin';
        $user->contactNumber = '09177777634';
        $user->email = 'jhaypee@valleypoint.com';
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
