<?php

use Illuminate\Cache\Repository as Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefreshTokensTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('refresh_tokens.table'), function (Blueprint $table) {
            $key = config('refresh_tokens.user.foreign_key');
            $parentKey = config('refresh_tokens.user.id');
            $type = config('refresh_tokens.user.key_type');
            $tokenLength = config('refresh_tokens.token_length');
            $table->bigIncrements('id');
            $table->string('token', $tokenLength)->unique();
            $table->dateTime('expires');
            
            // User Id
            $table->{$type}($key);
            
            $table->foreign($key)
                ->references($parentKey)
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $refreshTokensTable = config('refresh_tokens.table');
        $foreignKey = $key = config('refresh_tokens.user.foreign_key');
    
        Schema::table($refreshTokensTable, function (Blueprint $table) use ($refreshTokensTable, $foreignKey) {
            $table->dropForeign("{$table}_{$foreignKey}_foreign");
        });
        Schema::dropIfExists('refresh_tokens');
    }
}
