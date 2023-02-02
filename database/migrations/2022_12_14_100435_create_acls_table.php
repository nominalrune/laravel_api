<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_acls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->boolean('read')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->boolean('share')->default(false);
        });
        Schema::create('record_acls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->boolean('read')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->boolean('share')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_acls');
        Schema::dropIfExists('record_acls');
    }
};
