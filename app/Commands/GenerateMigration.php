<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class GenerateMigration extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'make:migration_from_db';
    protected $description = 'Generate migration files automatically from current database.';

    public function run(array $params)
    {
        $db = Database::connect();
        $database = $db->getDatabase();

        $query = $db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' ORDER BY TABLE_NAME, ORDINAL_POSITION");

        $result = $query->getResultArray();
        if (! $result) {
            CLI::error("⚠️ Tidak ada tabel ditemukan di database: {$database}");
            return;
        }

        $tables = [];
        foreach ($result as $row) {
            $tables[$row['TABLE_NAME']][] = $row;
        }

        foreach ($tables as $table => $columns) {
            $migration = "<?php\n\nnamespace App\Database\Migrations;\n\nuse CodeIgniter\\Database\\Migration;\n\nclass Create_" . ucfirst($table) . " extends Migration\n{\n\tpublic function up()\n\t{\n\t\t\$this->forge->addField([\n";

            foreach ($columns as $col) {
                $migration .= "\t\t\t'{$col['COLUMN_NAME']}' => [\n";
                $migration .= "\t\t\t\t'type' => '" . strtoupper(preg_replace('/\(.*/', '', $col['COLUMN_TYPE'])) . "',\n";

                if (preg_match('/\((\d+)\)/', $col['COLUMN_TYPE'], $m)) {
                    $migration .= "\t\t\t\t'constraint' => " . $m[1] . ",\n";
                }

                if ($col['IS_NULLABLE'] === 'NO') {
                    $migration .= "\t\t\t\t'null' => false,\n";
                }

                if ($col['COLUMN_DEFAULT'] !== null) {
                    $migration .= "\t\t\t\t'default' => '" . addslashes($col['COLUMN_DEFAULT']) . "',\n";
                }

                if (strpos($col['EXTRA'], 'auto_increment') !== false) {
                    $migration .= "\t\t\t\t'auto_increment' => true,\n";
                }

                $migration .= "\t\t\t],\n";
            }

            $migration .= "\t\t]);\n";
            $migration .= "\t\t\$this->forge->createTable('{$table}');\n\t}\n\n\tpublic function down()\n\t{\n\t\t\$this->forge->dropTable('{$table}');\n\t}\n}\n";

            $filename = APPPATH . 'Database/Migrations/' . date('YmdHis') . '_Create_' . $table . '.php';
            file_put_contents($filename, $migration);

            CLI::write("✅ Generated migration for table: {$table}", 'green');
            sleep(1); // jaga timestamp unik
        }

        CLI::write("Semua migrasi berhasil dibuat di folder app/Database/Migrations/", 'yellow');
    }
}
