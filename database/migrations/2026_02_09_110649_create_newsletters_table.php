<?php

use App\NewsletterStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id()->from(1001);

            $table->string('title');
            $table->string('content');
            $table->string('status')->default(NewsletterStatusEnum::draft->value);
            $table->foreignUlid('batch_id')->constrained('job_batches')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
