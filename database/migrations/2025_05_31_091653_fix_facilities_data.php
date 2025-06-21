<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix facilities data for fields where it's double JSON encoded
        DB::table('fields')->get()->each(function($field) {
            $facilities = $field->facilities;
            
            // Check if facilities is a string (incorrectly encoded)
            if (is_string($facilities) && !is_null($facilities)) {
                try {
                    // Try to decode it to see if it's a valid JSON string
                    $decoded = json_decode($facilities);
                    
                    // Update the field with the correctly formatted facilities
                    DB::table('fields')
                        ->where('id', $field->id)
                        ->update(['facilities' => $facilities]); // No need to re-encode, save as is
                } catch (\Exception $e) {
                    // If there's an error decoding, set to empty array
                    DB::table('fields')
                        ->where('id', $field->id)
                        ->update(['facilities' => json_encode([])]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse this operation
    }
};
