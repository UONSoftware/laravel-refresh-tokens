<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefreshTokensTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'refresh_tokens',
            function (Blueprint $table) {
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
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $foreignKey = config('refresh_tokens.user.foreign_key');

        Schema::table(
            'refresh_tokens',
            function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign("refresh_tokens_{$foreignKey}_foreign");
            }
        );
        Schema::dropIfExists('refresh_tokens');
    }
}
