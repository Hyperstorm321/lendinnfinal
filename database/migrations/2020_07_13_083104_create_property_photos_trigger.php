<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPhotosTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_property_photos_audit ON property_photos
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
                                INSERT INTO property_photos_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_photo_id,
                                    property_id,
                                    photo_src
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.property_photo_id,
                                        d.property_id,
                                        d.photo_src

                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO property_photos_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_photo_id,
                                    property_id,
                                    photo_src
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.property_photo_id,
                                        d.property_id,
                                        d.photo_src
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO property_photos_audit (
                            modified_by,
                            modified_date,
                            operation,
                            property_photo_id,
                            property_id,
                            photo_src
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.property_photo_id,
                                i.property_id,
                                i.photo_src
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
        DB::unprepared('DROP TRIGGER tr_property_photos_audit');
    }
}
