<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedSmallInteger('pax')->default(1)->after('trip_date');
            // scheme (full/deposit/paylater) is separate from payment_method (paypal/whatsapp), which is the channel
            $table->enum('payment_scheme', ['full', 'deposit', 'paylater'])->default('full')->after('payment_method');
            // snapshotted at booking time, same as total_price, so later config changes don't retroactively change what's owed
            $table->decimal('deposit_amount', 12, 2)->nullable()->after('payment_scheme');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['pax', 'payment_scheme', 'deposit_amount']);
        });
    }
};
