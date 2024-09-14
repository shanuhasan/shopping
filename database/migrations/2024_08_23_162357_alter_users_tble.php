<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('guid', 36)->unique()->nullable()->after('id');
            $table->integer('role_id')->nullable()->default(2)->after('email');
            $table->string('phone')->nullable()->after('role_id');
            $table->string('image')->nullable()->after('phone');
            $table->string('address_1')->nullable()->after('image');
            $table->string('address_2')->nullable()->after('address_1');
            $table->string('pincode')->nullable()->after('address_2');
            $table->string('country')->nullable()->after('pincode');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->tinyInteger('status')->nullable()->default(1)->after('remember_token');
            $table->tinyInteger('is_deleted')->nullable()->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('guid');
            $table->dropColumn('role_id');
            $table->dropColumn('status');
            $table->dropColumn('is_deleted');
            $table->dropColumn('phone');
            $table->dropColumn('image');
            $table->dropColumn('address_1');
            $table->dropColumn('address_2');
            $table->dropColumn('pincode');
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('city');
        });
    }
};
