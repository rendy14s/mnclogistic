<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Alter_Tables extends Migration
{
	public function up()
	{
		$this->db->query("ALTER TABLE mnc_3rd_courier MODIFY COLUMN `id` BIGINT NOT NULL AUTO_INCREMENT;");
	}

	public function down()
	{
		// $this->db->query("ALTER TABLE `mnc_3rd_courier` DROP COLUMN `id`;");

	}
}
