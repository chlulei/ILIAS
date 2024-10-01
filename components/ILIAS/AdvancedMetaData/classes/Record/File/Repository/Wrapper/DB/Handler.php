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

namespace ILIAS\AdvancedMetaData\Record\File\Repository\Wrapper\DB;

use ilDBInterface;
use ILIAS\AdvancedMetaData\Record\File\I\Repository\Key\HandlerInterface as ilAMDRecordFileRepositoryKeyInterface;
use ILIAS\AdvancedMetaData\Record\File\I\Repository\Values\HandlerInterface as ilAMDRecordFileRepositoryValuesInterface;
use ILIAS\AdvancedMetaData\Record\File\I\Repository\Wrapper\DB\HandlerInterface as ilAMDRecordFileRepositoryDBWrapperInterface;

class Handler implements ilAMDRecordFileRepositoryDBWrapperInterface
{
    protected ilDBInterface $db;

    public function __construct(
        ilDBInterface $db
    ) {
        $this->db = $db;
    }

    public function insert(
        ilAMDRecordFileRepositoryKeyInterface $key,
        ilAMDRecordFileRepositoryValuesInterface $values
    ): void {
        // TODO: Implement insert() method.
    }

    public function delete(
        ilAMDRecordFileRepositoryKeyInterface $key
    ): void {
        // TODO: Implement delete() method.
    }

    public function select(
        ilAMDRecordFileRepositoryKeyInterface $key
    ): void {
        // TODO: Implement select() method.
    }

    public function buildSelectQuery(
        ilAMDRecordFileRepositoryKeyInterface $key
    ): string {
        // TODO: Implement buildSelectQuery() method.
    }

    public function buildDeleteQuery(
        ilAMDRecordFileRepositoryKeyInterface $key
    ): string {
        // TODO: Implement buildDeleteQuery() method.
    }

    public function buildInsertQuery(
        ilAMDRecordFileRepositoryKeyInterface $key,
        ilAMDRecordFileRepositoryValuesInterface $values
    ): string {
        // TODO: Implement buildInsertQuery() method.
    }
}
