<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

class ilExportDBUpdateSteps implements ilDatabaseUpdateSteps
{
    protected ilDBInterface $db;

    public function prepare(ilDBInterface $db): void
    {
        $this->db = $db;
    }

    /**
     * Create new export file table
     */
    public function step_1(): void
    {
        if ($this->db->tableExists("export_files")) {
            return;
        }
        $this->db->createTable("export_files", [
            'object_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'rid' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
            'owner_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'timestamp' => [
                'type' => 'timestamp',
                'notnull' => false
            ],
        ]);
        $this->db->addPrimaryKey("export_files", ["object_id", "rid"]);
    }

    /**
     * Create table to store info about the public access file of an object
     */
    public function step_2(): void
    {
        if ($this->db->tableExists("export_public_access")) {
            return;
        }
        $this->db->createTable("export_public_access", [
            'object_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'type' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
            'identification' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
            'timestamp' => [
                'type' => 'timestamp',
                'notnull' => false
            ],
        ]);
        $this->db->addPrimaryKey("export_public_access", ["object_id"]);
    }

    /**
     * Create table to store info about with file types are allowed for public access for an object
     */
    public function step_3(): void
    {
        if ($this->db->tableExists("export_pub_acc_types")) {
            return;
        }
        $this->db->createTable("export_pub_acc_types", [
            'object_id' => [
                'type' => 'integer',
                'length' => 8,
                'default' => 0,
                'notnull' => true
            ],
            'type' => [
                'type' => 'text',
                'length' => 64,
                'default' => '',
                'notnull' => true
            ],
            'timestamp' => [
                'type' => 'timestamp',
                'notnull' => true
            ],
        ]);
        $this->db->addPrimaryKey("export_pub_acc_types", ["object_id", "type"]);
    }

    /**
     * Add migrate column to table export_file_info
     */
    public function step_4(): void
    {
        if (!$this->db->tableExists("export_file_info")) {
            return;
        }
        if (
            $this->db->tableColumnExists("export_file_info", "migrated")
        ) {
            return;
        }
        $this->db->addTableColumn("export_file_info", "migrated", [
            'type' => ilDBConstants::T_INTEGER,
            'length' => 4,
            'default' => 0
        ]);
    }
}
