<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_transactions_audit ON transactions
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
                                INSERT INTO transactions_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    transaction_id,
                                    user_id,
                                    property_id,
                                    transaction_type_id,
                                    message
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.transaction_id,
                                        d.user_id,
                                        d.property_id,
                                        d.transaction_type_id,
                                        d.message
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO transactions_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    transaction_id,
                                    user_id,
                                    property_id,
                                    transaction_type_id,
                                    message
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.transaction_id,
                                        d.user_id,
                                        d.property_id,
                                        d.transaction_type_id,
                                        d.message
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO transactions_audit (
                            modified_by,
                            modified_date,
                            operation,
                            transaction_id,
                            user_id,
                            property_id,
                            transaction_type_id,
                            message
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.transaction_id,
                                i.user_id,
                                i.property_id,
                                i.transaction_type_id,
                                i.message
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
        DB::unprepared('DROP TRIGGER tr_transactions_audit');
    }
}
