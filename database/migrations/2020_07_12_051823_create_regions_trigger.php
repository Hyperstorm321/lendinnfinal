<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_regions_audit ON regions
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
                                INSERT INTO regions_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    region_id,
                                    region
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.region_id,
                                        d.region
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO regions_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    region_id,
                                    region
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.region_id,
                                        d.region
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO regions_audit (
                            modified_by,
                            modified_date,
                            operation,
                            region_id,
                            region
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.region_id,
                                i.region
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
        DB::unprepared('DROP TRIGGER tr_regions_audit');
    }
}
