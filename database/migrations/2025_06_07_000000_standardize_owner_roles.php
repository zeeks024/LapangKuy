<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to ensure owner role works correctly.
     */
    public function up(): void
    {
        // Update any field_owner roles to owner for consistency
        DB::table('users')
            ->where('role', 'field_owner')
            ->update(['role' => 'owner']);
            
        // Add note that valid roles are: admin, owner, user
        // This is just a comment in the migration, not actual schema change
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration doesn't need a down method since we're just standardizing values
    }
};
