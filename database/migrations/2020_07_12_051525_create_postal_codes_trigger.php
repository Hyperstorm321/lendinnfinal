<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_postal_codes_audit ON postal_codes
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
                                INSERT INTO postal_codes_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    postal_code_id,
                                    postal_code
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.postal_code_id,
                                        d.postal_code
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO postal_codes_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    postal_code_id,
                                    postal_code
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.postal_code_id,
                                        d.postal_code
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO postal_codes_audit (
                            modified_by,
                            modified_date,
                            operation,
                            postal_code_id,
                            postal_code
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.postal_code_id,
                                i.postal_code
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
        DB::unprepared('DROP TRIGGER tr_postal_codes_audit');
    }
}
