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
        Schema::table('users', function (Blueprint $table) {
            // Ajouter les champs de profil s'ils n'existent pas
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'sexe')) {
                $table->enum('sexe', ['M', 'F', 'Autre'])->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'residence')) {
                $table->string('residence')->nullable()->after('sexe');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'responsable_services', 'gestion_client', 'client'])->default('client')->after('residence');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code')->nullable()->unique()->after('is_active');
            }
            if (!Schema::hasColumn('users', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('verification_code');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'sexe',
                'residence',
                'role',
                'is_active',
                'verification_code',
                'created_by',
                'deleted_at',
            ]);
        });
    }
};
