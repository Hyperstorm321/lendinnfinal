<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangaysTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_barangays_audit ON barangays
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
                                INSERT INTO barangays_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    barangay_id,
                                    barangay,
                                    city_id
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.barangay_id,
                                        d.barangay,
                                        d.city_id
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO barangays_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    barangay_id,
                                    barangay,
                                    city_id
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.barangay_id,
                                        d.barangay,
                                        d.city_id
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO barangays_audit (
                            modified_by,
                            modified_date,
                            operation,
                            barangay_id,
                            barangay,
                            city_id
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.barangay_id,
                                i.barangay,
                                i.city_id
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
        DB::unprepared('DROP TRIGGER tr_barangays_audit');
    }
}
