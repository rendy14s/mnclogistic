<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Alter_Tables extends Migration
{
	public function up()
	{
		$this->db->query("ALTER TABLE `mnc_3rd_courier` ADD COLUMN `id` int NOT NULL AUTO_INCREMENT;");
	}

	public function down()
	{
		// DROP ONLY

	}
}
