<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tr_cities_audit ON cities
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
                                INSERT INTO cities_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    city_id,
                                    city
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'U',
                                        d.city_id,
                                        d.city
                                FROM deleted d
                            END
                        ELSE 
                            BEGIN
                                INSERT INTO cities_audit (
                                    modified_by,
                                    modified_date,
                                    operation,
                                    city_id,
                                    city
                                )
                                SELECT	@login_name,
                                        GETDATE(),
                                        'D',
                                        d.city_id,
                                        d.city
                                FROM deleted d
                            END
                    END
                ELSE 
                    BEGIN
                        INSERT INTO cities_audit (
                            modified_by,
                            modified_date,
                            operation,
                            city_id,
                            city
                        )
                        SELECT	@login_name,
                                GETDATE(),
                                'I',
                                i.city_id,
                                i.city
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
        DB::unprepared('DROP TRIGGER tr_cities_audit');
    }
}
