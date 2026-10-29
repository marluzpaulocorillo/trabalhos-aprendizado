<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('client_assessments', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('id')->constrained('clients')->cascadeOnDelete();
        });

        DB::table('client_assessments')
            ->select(['id', 'name'])
            ->orderBy('id')
            ->get()
            ->each(function (object $assessment): void {
                $clientId = DB::table('clients')->where('name', $assessment->name)->value('id');

                if ($clientId === null) {
                    $clientId = DB::table('clients')->insertGetId([
                        'name' => $assessment->name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('client_assessments')
                    ->where('id', $assessment->id)
                    ->update(['client_id' => $clientId]);
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_assessments', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });

        Schema::dropIfExists('clients');
    }
};
