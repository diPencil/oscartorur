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
        // 1. Create Countries Table
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        // 2. Add country_id to locations (cities)
        if (!Schema::hasColumn('locations', 'country_id')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->unsignedBigInteger('country_id')->nullable()->after('id');
            });
        }

        // 3. Create Areas Table
        if (!Schema::hasTable('areas')) {
            Schema::create('areas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('location_id'); // City
                $table->string('name');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        // 4. Update hotel_contracts
        if (!Schema::hasColumn('hotel_contracts', 'inventory_mode')) {
            Schema::table('hotel_contracts', function (Blueprint $table) {
                $table->string('inventory_mode')->default('free_sale')->after('currency_id');
            });
        }

        // 5. Update hotels table
        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'hotel_code')) {
                $table->string('hotel_code')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('hotels', 'property_type')) {
                $table->string('property_type')->default('Hotel')->after('slug');
            }
            if (!Schema::hasColumn('hotels', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('star_rating');
            }
            if (!Schema::hasColumn('hotels', 'area_id')) {
                $table->unsignedBigInteger('area_id')->nullable()->after('location_id');
            }
            if (!Schema::hasColumn('hotels', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('address');
            }
            if (!Schema::hasColumn('hotels', 'hotel_email')) {
                $table->string('hotel_email')->nullable();
                $table->string('reservation_email')->nullable();
                $table->string('phone')->nullable();
                $table->string('whatsapp')->nullable();
                $table->string('website')->nullable();
                $table->string('contact_person')->nullable();
                $table->text('short_description')->nullable();
            }

            // Rename supplier_id to primary_supplier_id if exists
            if (Schema::hasColumn('hotels', 'supplier_id')) {
                $table->renameColumn('supplier_id', 'primary_supplier_id');
            }
        });

        // Change status column and primary_supplier_id
        Schema::table('hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_supplier_id')->nullable()->change();
        });
        
        DB::statement("ALTER TABLE hotels MODIFY status VARCHAR(50) DEFAULT 'draft'");

        // 6. Data Migration
        // Transfer inventory_mode from hotels to hotel_contracts
        if (Schema::hasColumn('hotels', 'inventory_mode')) {
            $hotels = DB::table('hotels')->get();
            foreach ($hotels as $hotel) {
                $mode = $hotel->inventory_mode ?? 'free_sale';
                DB::table('hotel_contracts')
                    ->where('hotel_id', $hotel->id)
                    ->update(['inventory_mode' => $mode]);
                
                // Also assign a hotel code if missing
                if (empty($hotel->hotel_code)) {
                    $code = 'HTL-' . str_pad($hotel->id, 6, '0', STR_PAD_LEFT);
                    DB::table('hotels')->where('id', $hotel->id)->update(['hotel_code' => $code]);
                }
            }

            // 7. Drop inventory_mode from hotels
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropColumn('inventory_mode');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_tables_and_revamp_hotels');
    }
};
