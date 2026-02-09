<?php

use App\NewsletterRecipientStatusEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_recipients', function (Blueprint $table) {
            $table->id()->from(1001);

            $table->foreignId('newsletter_id')->constrained('newsletters')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('status')->default(NewsletterRecipientStatusEnum::pending->value);

            $table->unique(['newsletter_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_recipient');
    }
};
