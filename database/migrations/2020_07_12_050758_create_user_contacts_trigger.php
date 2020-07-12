<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserContactsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_user_contacts_audit ON user_contacts
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
                                INSERT INTO user_contacts_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    user_contact_id,
                                    user_id,
                                    contact_no
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.user_contact_id,
                                        d.user_id,
                                        d.contact_no
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO user_contacts_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    user_contact_id,
                                    user_id,
                                    contact_no
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.user_contact_id,
                                        d.user_id,
                                        d.contact_no
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO user_contacts_audit (
                            modified_by,
                            modified_date,
                            operation,
                            user_contact_id,
                            user_id,
                            contact_no
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.user_contact_id,
                                i.user_id,
                                i.contact_no
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
        DB::unprepared('DROP TRIGGER tr_user_contacts_audit');
    }
}
