
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // gallery_images column already exists.
        // No change required.
    }

    public function down(): void
    {
        // Keep the existing gallery_images column intact.
    }
};
