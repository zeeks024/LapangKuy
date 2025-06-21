<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingPaymentFieldsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Cek apakah kolom payment_method belum ada
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
            
            // Cek apakah kolom payment_token belum ada
            if (!Schema::hasColumn('bookings', 'payment_token')) {
                $table->string('payment_token')->nullable()->after('transaction_id');
            }
            
            // Tambahkan kolom paid_at untuk mencatat kapan pembayaran selesai
            if (!Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_token', 'paid_at']);
        });
    }
};
