<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('blogs')) {
            return;
        }

        $columns = collect(['is_external', 'external_url', 'source_credit'])
            ->filter(fn (string $column) => Schema::hasColumn('blogs', $column))
            ->values()
            ->all();

        if ($columns === []) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (! Schema::hasColumn('blogs', 'is_external')) {
                $table->boolean('is_external')->default(false);
            }
            if (! Schema::hasColumn('blogs', 'external_url')) {
                $table->string('external_url', 500)->nullable();
            }
            if (! Schema::hasColumn('blogs', 'source_credit')) {
                $table->string('source_credit')->nullable();
            }
        });
    }
};
