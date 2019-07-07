<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExternalProviderInfoToProjectExternalUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_external_url', function (Blueprint $table) {
            $table->string('provider_name')->after('project_id');
            $table->string('external_id')->after('provider_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_external_url', function (Blueprint $table) {
            $table->dropColumn('provider_name');
            $table->dropColumn('external_id');
        });
    }
}
