<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixFacilitiesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-facilities-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix facilities data that might be improperly encoded';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing facilities data...');
        
        $fields = \App\Models\Field::all();
        $fixedCount = 0;
        
        foreach ($fields as $field) {
            $facilities = $field->facilities;
            
            // If facilities is a string or not an array, fix it
            if (!is_array($facilities)) {
                try {
                    if (is_string($facilities) && !is_null($facilities)) {
                        // Try to decode it to see if it's a valid JSON string
                        $decoded = json_decode($facilities, true);
                        
                        if (is_array($decoded)) {
                            $field->facilities = $decoded;
                        } else {
                            $field->facilities = [];
                        }
                    } else {
                        $field->facilities = [];
                    }
                    
                    $field->save();
                    $fixedCount++;
                    
                } catch (\Exception $e) {
                    $this->error("Error fixing field ID {$field->id}: {$e->getMessage()}");
                }
            }
        }
        
        $this->info("Fixed {$fixedCount} field records.");
    }
}
