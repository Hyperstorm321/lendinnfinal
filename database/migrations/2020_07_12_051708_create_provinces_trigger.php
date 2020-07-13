<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_provinces_audit ON provinces
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
                                INSERT INTO provinces_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    province_id,
                                    province,
                                    region_id
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.province_id,
                                        d.province,
                                        d.region_id
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO provinces_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    province_id,
                                    province,
                                    region_id
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.province_id,
                                        d.province,
                                        d.region_id
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO provinces_audit (
                            modified_by,
                            modified_date,
                            operation,
                            province_id,
                            province,
                            region_id
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.province_id,
                                i.province,
                                i.region_id
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
        DB::unprepared('DROP TRIGGER tr_provinces_audit');
    }
}
