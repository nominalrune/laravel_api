<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UserGroup;

return new class extends Migration
{
    private $tables=['users','user_groups','tasks','records','acls'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_acls', function (Blueprint $table) {
            $table->enum('target_table',$this->tables);
            $table->foreignIdFor(UserGroup::class, 'user_group_id');
            $table->boolean('read')->default(false);
            $table->boolean('create')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->boolean('share')->default(false);
        });
        Schema::create('acls', function (Blueprint $table) {
            $table->id();
            $table->enum('target_table',$this->tables);
            $table->foreignId('target_id');
            $table->foreignIdFor(UserGroup::class, 'user_group_id');
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
    }
};
