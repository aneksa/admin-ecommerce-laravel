<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoriesAddCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('categories', 'code')) {
            Schema::table('categories', function (Blueprint $table) {
                //
                $table->string('code')->unique()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('categories', 'code')) {
            Schema::table('categories', function (Blueprint $table) {
                //
                $table->dropColumn('code');
            });
        }
    }
}
