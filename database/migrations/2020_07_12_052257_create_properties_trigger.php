<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_properties_audit ON properties
                FOR INSERT, UPDATE, DELETE
            AS
            BEGIN
                DECLARE @login_name VARCHAR(128)
            
                SELECT	@login_name = login_name
                FROM	sys.dm_exec_sessions
                WHERE	session_id = @@SPID
            
                IF EXISTS (SELECT 0 FROM deleted)
                    BEGIN 
                        IF EXISTS (SELECT 0 FROM inserted)
                            BEGIN
                                INSERT INTO properties_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_id,
                                    landlord_id,
                                    property_type_id,
                                    name,
                                    price_peso,
                                    land_size,
                                    living_size,
                                    features,
                                    details,
                                    is_pet_allowed,
                                    pet_allowed_comment,
                                    no_of_bedrooms,
                                    no_of_bathrooms,
                                    is_salable,
                                    detailed_address,
                                    region_id,
                                    province_id,
                                    city_id,
                                    barangay_id,
                                    postal_code_id
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.property_id,
                                        d.landlord_id,
                                        d.property_type_id,
                                        d.name,
                                        d.price_peso,
                                        d.land_size,
                                        d.living_size,
                                        d.features,
                                        d.details,
                                        d.is_pet_allowed,
                                        d.pet_allowed_comment,
                                        d.no_of_bedrooms,
                                        d.no_of_bathrooms,
                                        d.is_salable,
                                        d.detailed_address,
                                        d.region_id,
                                        d.province_id,
                                        d.city_id,
                                        d.barangay_id,
                                        d.postal_code_id
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO properties_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_id,
                                    landlord_id,
                                    property_type_id,
                                    name,
                                    price_peso,
                                    land_size,
                                    living_size,
                                    features,
                                    details,
                                    is_pet_allowed,
                                    pet_allowed_comment,
                                    no_of_bedrooms,
                                    no_of_bathrooms,
                                    is_salable,
                                    detailed_address,
                                    region_id,
                                    province_id,
                                    city_id,
                                    barangay_id,
                                    postal_code_id
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.property_id,
                                        d.landlord_id,
                                        d.property_type_id,
                                        d.name,
                                        d.price_peso,
                                        d.land_size,
                                        d.living_size,
                                        d.features,
                                        d.details,
                                        d.is_pet_allowed,
                                        d.pet_allowed_comment,
                                        d.no_of_bedrooms,
                                        d.no_of_bathrooms,
                                        d.is_salable,
                                        d.detailed_address,
                                        d.region_id,
                                        d.province_id,
                                        d.city_id,
                                        d.barangay_id,
                                        d.postal_code_id
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO properties_audit (
                            modified_by,
                            modified_date,
                            operation,
                            property_id,
                            landlord_id,
                            property_type_id,
                            name,
                            price_peso,
                            land_size,
                            living_size,
                            features,
                            details,
                            is_pet_allowed,
                            pet_allowed_comment,
                            no_of_bedrooms,
                            no_of_bathrooms,
                            is_salable,
                            detailed_address,
                            region_id,
                            province_id,
                            city_id,
                            barangay_id,
                            postal_code_id
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.property_id,
                                i.landlord_id,
                                i.property_type_id,
                                i.name,
                                i.price_peso,
                                i.land_size,
                                i.living_size,
                                i.features,
                                i.details,
                                i.is_pet_allowed,
                                i.pet_allowed_comment,
                                i.no_of_bedrooms,
                                i.no_of_bathrooms,
                                i.is_salable,
                                i.detailed_address,
                                i.region_id,
                                i.province_id,
                                i.city_id,
                                i.barangay_id,
                                i.postal_code_id
                        FROM inserted i
                    END
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER tr_properties_audit');
    }
}
