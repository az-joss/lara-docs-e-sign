<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained();
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('signer_id')->constrained('users');
            $table->foreignId('signature_id')->nullable()->constrained();
            $table->enum('status', ['pending', 'rejected', 'signed']);
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unique(['document_id', 'signer_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_signatures', function(Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['signer_id']);
            $table->dropForeign(['document_id']);
            $table->dropForeign(['signature_id']);
            $table->dropIfExists();
        });
    }
};
