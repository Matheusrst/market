<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * envio dos produtos para o banco de dados
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * reverse the migration
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
