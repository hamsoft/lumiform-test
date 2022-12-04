<?php

namespace Tests\Feature\Models;

use Illuminate\Support\Facades\Schema;

trait ColumnsChecker
{

    protected function checkColumns(string $table, array $columns): void
    {
        $tableColumns = Schema::getColumnListing($table);

        foreach ($columns as $column) {
            $this->assertContains($column, $tableColumns, "Table '$table' does not contain column '$column' ");
        }
    }
}
