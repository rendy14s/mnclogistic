<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class DiffMigration extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'diff:migration';
    protected $description = 'Generate migration file automatically based on database structure differences.';
    protected $usage = 'php spark diff:migration [--sql]';

    private $snapshotFile;

    public function __construct()
    {
        $this->snapshotFile = WRITEPATH . 'schema_snapshot.json';
    }

    public function run(array $params)
    {
        $db = Database::connect();
        $schema = $db->getDatabase();
        $current = $this->getDatabaseSchema($db, $schema);
        $old = file_exists($this->snapshotFile)
            ? json_decode(file_get_contents($this->snapshotFile), true)
            : [];

        $diff = $this->compareSchemas($old, $current);
        if (empty($diff)) {
            CLI::write('No differences detected between current DB and snapshot.', 'yellow');
            return;
        }

        $timestamp = date('YmdHis');
        $filename = "{$timestamp}_Alter_Tables.php";
        $path = APPPATH . "Database/Migrations/{$filename}";

        $migration = "<?php\n\nnamespace App\\Database\\Migrations;\n\nuse CodeIgniter\\Database\\Migration;\n\n";
        $migration .= "class Alter_Tables extends Migration\n{\n";
        $migration .= "\tpublic function up()\n\t{\n";

        foreach ($diff as $table => $changes) {
            $migration .= $this->generateSQL($table, $changes, $direction = 'up');
        }

        $migration .= "\t}\n\n\tpublic function down()\n\t{\n";

        foreach ($diff as $table => $changes) {
            $migration .= $this->generateSQL($table, $changes, $direction = 'down');
        }

        $migration .= "\t}\n}\n";

        file_put_contents($path, $migration);
        CLI::write("Migration file created: {$path}", 'green');

        // update snapshot
        file_put_contents($this->snapshotFile, json_encode($current, JSON_PRETTY_PRINT));
        CLI::write('Snapshot updated.', 'light_blue');
    }

    /**
     * Ambil struktur database saat ini dari INFORMATION_SCHEMA
     */
    private function getDatabaseSchema($db, $schema)
    {
        $result = $db->query("
            SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = '{$schema}'
        ")->getResultArray();

        $tables = [];
        foreach ($result as $row) {
            $tables[$row['TABLE_NAME']][$row['COLUMN_NAME']] = [
                'column_type_raw' => strtolower($row['COLUMN_TYPE']),
                'type' => strtoupper(preg_replace('/\(.*/', '', $row['COLUMN_TYPE'])),
                'length' => $this->extractLength($row['COLUMN_TYPE']),
                'null' => $row['IS_NULLABLE'] === 'YES',
                'default' => $row['COLUMN_DEFAULT'],
                'auto_increment' => str_contains($row['EXTRA'], 'auto_increment'),
            ];
        }
        return $tables;
    }

    /**
     * Ekstrak panjang kolom dari tipe MySQL (contoh VARCHAR(255) → 255)
     */
    private function extractLength($type)
    {
        if (preg_match('/\((\d+)\)/', $type, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    /**
     * Bandingkan snapshot lama dan struktur baru
     */
    private function compareSchemas($old, $new)
    {
        $diff = [];

        foreach ($new as $table => $cols) {
            if (!isset($old[$table])) {
                $diff[$table] = ['added' => $cols, 'removed' => [], 'changed' => []];
                continue;
            }

            $added = array_diff_key($cols, $old[$table]);
            $removed = array_diff_key($old[$table], $cols);
            $changed = [];

            foreach ($cols as $col => $def) {
                if (!isset($old[$table][$col])) continue;

                $oldCol = $old[$table][$col];
                if (
                    $def['column_type_raw'] !== $oldCol['column_type_raw'] ||
                    $def['null'] !== $oldCol['null'] ||
                    $def['default'] !== $oldCol['default'] ||
                    $def['auto_increment'] !== $oldCol['auto_increment']
                ) {
                    $changed[$col] = ['old' => $oldCol, 'new' => $def];
                }
            }

            if ($added || $removed || $changed) {
                $diff[$table] = compact('added', 'removed', 'changed');
            }
        }

        foreach ($old as $table => $cols) {
            if (!isset($new[$table])) {
                $diff[$table] = ['added' => [], 'removed' => $cols, 'changed' => []];
            }
        }

        return $diff;
    }

    /**
     * Generate SQL berdasarkan perbedaan skema
     */
    private function generateSQL($table, $changes, $direction = 'up')
    {
        $sql = '';

        if ($direction === 'up') {
            foreach ($changes['added'] as $col => $def) {
                $sql .= "\t\t\$this->db->query(\"ALTER TABLE `{$table}` ADD COLUMN `{$col}` {$def['column_type_raw']} " .
                    ($def['null'] ? 'NULL' : 'NOT NULL') .
                    ($def['default'] !== null ? " DEFAULT '{$def['default']}'" : '') .
                    ($def['auto_increment'] ? ' AUTO_INCREMENT' : '') . ";\");\n";
            }

            foreach ($changes['removed'] as $col => $def) {
                $sql .= "\t\t\$this->db->query(\"ALTER TABLE `{$table}` DROP COLUMN `{$col}`;\");\n";
            }

            foreach ($changes['changed'] as $col => $pair) {
                $def = $pair['new'];
                $sql .= "\t\t\$this->db->query(\"ALTER TABLE `{$table}` MODIFY COLUMN `{$col}` {$def['column_type_raw']} " .
                    ($def['null'] ? 'NULL' : 'NOT NULL') .
                    ($def['default'] !== null ? " DEFAULT '{$def['default']}'" : '') .
                    ($def['auto_increment'] ? ' AUTO_INCREMENT' : '') . ";\");\n";
            }
        } else {
            // DOWN migration (rollback)
            foreach ($changes['added'] as $col => $def) {
                $sql .= "\t\t\$this->db->query(\"ALTER TABLE `{$table}` DROP COLUMN `{$col}`;\");\n";
            }

            foreach ($changes['removed'] as $col => $def) {
                $sql .= "\t\t\$this->db->query(\"ALTER TABLE `{$table}` ADD COLUMN `{$col}` {$def['column_type_raw']} " .
                    ($def['null'] ? 'NULL' : 'NOT NULL') .
                    ($def['default'] !== null ? " DEFAULT '{$def['default']}'" : '') .
                    ($def['auto_increment'] ? ' AUTO_INCREMENT' : '') . ";\");\n";
            }

            foreach ($changes['changed'] as $col => $pair) {
                $def = $pair['old'];
                $sql .= "\t\t\$this->db->query(\"ALTER TABLE `{$table}` MODIFY COLUMN `{$col}` {$def['column_type_raw']} " .
                    ($def['null'] ? 'NULL' : 'NOT NULL') .
                    ($def['default'] !== null ? " DEFAULT '{$def['default']}'" : '') .
                    ($def['auto_increment'] ? ' AUTO_INCREMENT' : '') . ";\");\n";
            }
        }

        return $sql . "\n";
    }
}
