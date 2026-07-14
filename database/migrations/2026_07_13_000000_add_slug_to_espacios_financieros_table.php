<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('espacios_financieros', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nombre');
        });

        DB::table('espacios_financieros')
            ->orderBy('id')
            ->get(['id', 'nombre'])
            ->each(function (object $espacio): void {
                $baseSlug = Str::slug($espacio->nombre) ?: 'espacio';
                $slug = $baseSlug;
                $suffix = 2;

                while (DB::table('espacios_financieros')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$suffix++;
                }

                DB::table('espacios_financieros')
                    ->where('id', $espacio->id)
                    ->update(['slug' => $slug]);
            });

        Schema::table('espacios_financieros', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('espacios_financieros', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
