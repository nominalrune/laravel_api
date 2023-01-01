<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->boolean('is_individual')->default(false);
        });
        Schema::create('user_group_members', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->foreignIdFor(UserGroup::class, 'group_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->tinyInteger('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
};
