<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_id')->nullable()->after('title_en');
            $table->string('excerpt_en', 300)->nullable()->after('excerpt');
            $table->string('excerpt_id', 300)->nullable()->after('excerpt_en');
            $table->longText('content_en')->nullable()->after('content');
            $table->longText('content_id')->nullable()->after('content_en');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['title', 'excerpt', 'content']);
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('content')->nullable();
            $table->dropColumn([
                'title_en', 'title_id',
                'excerpt_en', 'excerpt_id',
                'content_en', 'content_id',
            ]);
        });
    }
};
