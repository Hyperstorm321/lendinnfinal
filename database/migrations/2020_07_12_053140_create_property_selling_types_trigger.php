<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertySellingTypesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_property_selling_types_audit ON property_selling_types
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
                                INSERT INTO property_selling_types_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_selling_type_id,
                                    property_id,
                                    selling_type_id,
                                    max_quantity,
                                    available_quantity
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.property_selling_type_id,
                                        d.property_id,
                                        d.selling_type_id,
                                        d.max_quantity,
                                        d.available_quantity
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO property_selling_types_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    property_selling_type_id,
                                    property_id,
                                    selling_type_id,
                                    max_quantity,
                                    available_quantity
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.property_selling_type_id,
                                        d.property_id,
                                        d.selling_type_id,
                                        d.max_quantity,
                                        d.available_quantity
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO property_selling_types_audit (
                            modified_by,
                            modified_date,
                            operation,
                            property_selling_type_id,
                            property_id,
                            selling_type_id,
                            max_quantity,
                            available_quantity
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.property_selling_type_id,
                                i.property_id,
                                i.selling_type_id,
                                i.max_quantity,
                                i.available_quantity
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
        DB::unprepared('DROP TRIGGER tr_property_selling_types_audit');
    }
}
