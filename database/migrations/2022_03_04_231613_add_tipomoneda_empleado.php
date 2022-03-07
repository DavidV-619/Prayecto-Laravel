<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipomonedaEmpleado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleado', function (Blueprint $table) {
            /* $table->tinyInteger('tipo_moneda')->after('activo'); */
            $table->enum('tipo_moneda', ['EUR', 'MXN', 'USD', 'CAD', 'YEN' ])->after('activo');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleado', function (Blueprint $table) {
            $table->dropColumn('tipo_moneda');
        });
    }
}
