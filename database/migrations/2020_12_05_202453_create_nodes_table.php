<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     * 节点表
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name',50)->comment('节点名称');
            $table->string('route_name',100)->default('')->comment('路由别名，权限认证标识');
            $table->unsignedBigInteger('pid')->default(0)->comment('上级ID');
            $table->enum('is_menu',['0','1'])->default('0')->comment('是否为菜单');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
    }
}
