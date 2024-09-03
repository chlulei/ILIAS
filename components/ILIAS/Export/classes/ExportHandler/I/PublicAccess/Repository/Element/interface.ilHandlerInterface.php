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

namespace ILIAS\Export\ExportHandler\I\PublicAccess\Repository\Element;

use DateTimeImmutable;
use ILIAS\Data\ObjectId;

interface ilHandlerInterface
{
    public function withObjectId(ObjectId $object_id): ilHandlerInterface;

    public function withIdentification(string $identification): ilHandlerInterface;

    public function withType(string $type): ilHandlerInterface;

    public function getType(): string;

    public function getIdentification(): string;

    public function getObjectId(): ObjectId;

    public function getLastModified(): DateTimeImmutable;

    public function download(string $zip_file_name = ""): void;

    public function isStorable(): bool;
}
