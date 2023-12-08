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
            $table->string('firstname')->default('firstname')->nullable()->after('name');
            $table->string('lastname')->default('lastname')->nullable()->after('firstname');
            $table->boolean('premia')->default(false)->after('lastname');
            $table->boolean('status')->default(false)->after('premia');
            $table->string('privileges')->default('regular')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('premia');
            $table->dropColumn('status');
            $table->dropColumn('privileges');
        });
    }
};
