<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(){
    Schema::table('maintenance_requests', function (Blueprint $table) {
        $table->unsignedTinyInteger('rating')->nullable()->comment('تقييم الطلب من 1 إلى 5');
    });
}


public function down()
{
    Schema::table('maintenance_requests', function (Blueprint $table) {
        $table->dropColumn('rating');
    });

}
};
