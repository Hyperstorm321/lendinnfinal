<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_users_audit ON users
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
                                INSERT INTO users_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    user_id,
                                    email,
                                    email_verified_at,
                                    password,
                                    remember_token,
                                    username,
                                    first_name,
                                    middle_name,
                                    last_name,
                                    extension_name,
                                    detailed_address,
                                    region_id,
                                    province_id,
                                    city_id,
                                    barangay_id,
                                    postal_code_id,
                                    photo_src,
                                    is_landlord
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.user_id,
                                        d.email,
                                        d.email_verified_at,
                                        d.password,
                                        d.remember_token,
                                        d.username,
                                        d.first_name,
                                        d.middle_name,
                                        d.last_name,
                                        d.extension_name,
                                        d.detailed_address,
                                        d.region_id,
                                        d.province_id,
                                        d.city_id,
                                        d.barangay_id,
                                        d.postal_code_id,
                                        d.photo_src,
                                        d.is_landlord
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO users_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    user_id,
                                    email,
                                    email_verified_at,
                                    password,
                                    remember_token,
                                    username,
                                    first_name,
                                    middle_name,
                                    last_name,
                                    extension_name,
                                    detailed_address,
                                    region_id,
                                    province_id,
                                    city_id,
                                    barangay_id,
                                    postal_code_id,
                                    photo_src,
                                    is_landlord
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.user_id,
                                        d.email,
                                        d.email_verified_at,
                                        d.password,
                                        d.remember_token,
                                        d.username,
                                        d.first_name,
                                        d.middle_name,
                                        d.last_name,
                                        d.extension_name,
                                        d.detailed_address,
                                        d.region_id,
                                        d.province_id,
                                        d.city_id,
                                        d.barangay_id,
                                        d.postal_code_id,
                                        d.photo_src,
                                        d.is_landlord
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO users_audit (
                            modified_by,
                            modified_date,
                            operation,
                            user_id,
                            email,
                            email_verified_at,
                            password,
                            remember_token,
                            username,
                            first_name,
                            middle_name,
                            last_name,
                            extension_name,
                            detailed_address,
                            region_id,
                            province_id,
                            city_id,
                            barangay_id,
                            postal_code_id,
                            photo_src,
                            is_landlord
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.user_id,
                                i.email,
                                i.email_verified_at,
                                i.password,
                                i.remember_token,
                                i.username,
                                i.first_name,
                                i.middle_name,
                                i.last_name,
                                i.extension_name,
                                i.detailed_address,
                                i.region_id,
                                i.province_id,
                                i.city_id,
                                i.barangay_id,
                                i.postal_code_id,
                                i.photo_src,
                                i.is_landlord
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
        DB::unprepared('DROP TRIGGER tr_users_audit');
    }
}
