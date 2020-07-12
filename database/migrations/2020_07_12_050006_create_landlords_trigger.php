<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_landlords_audit ON landlords
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
                                INSERT INTO landlords_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    landlord_id,
                                    user_id,
                                    agency_name,
                                    membership_year
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.landlord_id,
                                        d.user_id,
                                        d.agency_name,
                                        d.membership_year
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO landlords_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    landlord_id,
                                    user_id,
                                    agency_name,
                                    membership_year
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.landlord_id,
                                        d.user_id,
                                        d.agency_name,
                                        d.membership_year
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO landlords_audit (
                            modified_by,
                            modified_date,
                            operation,
                            landlord_id,
                            user_id,
                            agency_name,
                            membership_year
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.landlord_id,
                                i.user_id,
                                i.agency_name,
                                i.membership_year
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
        DB::unprepared('DROP TRIGGER tr_landlords_audit');
    }
}
