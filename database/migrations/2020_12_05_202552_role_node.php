<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleNode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_node', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->default(0)->comment('角色ID');
            $table->unsignedBigInteger('node_id')->default(0)->comment('节点ID');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_node');
    }
}
