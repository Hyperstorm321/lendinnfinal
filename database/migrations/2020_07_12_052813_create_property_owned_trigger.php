<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyOwnedTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_property_owned_audit ON property_owned
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
                                INSERT INTO property_owned_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_owned_id,
                                    user_id,
                                    property_id,
                                    property_selling_type_id,
                                    quantity,
                                    date_acquired
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.property_owned_id,
                                        d.user_id,
                                        d.property_id,
                                        d.property_selling_type_id,
                                        d.quantity,
                                        d.date_acquired
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO property_owned_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_owned_id,
                                    user_id,
                                    property_id,
                                    property_selling_type_id,
                                    quantity,
                                    date_acquired
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.property_owned_id,
                                        d.user_id,
                                        d.property_id,
                                        d.property_selling_type_id,
                                        d.quantity,
                                        d.date_acquired
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO property_owned_audit (
                            modified_by,
                            modified_date,
                            operation,
                            property_owned_id,
                            user_id,
                            property_id,
                            property_selling_type_id,
                            quantity,
                            date_acquired
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.property_owned_id,
                                i.user_id,
                                i.property_id,
                                i.property_selling_type_id,
                                i.quantity,
                                i.date_acquired
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
        DB::unprepared('DROP TRIGGER tr_property_owned_audit');
    }
}
