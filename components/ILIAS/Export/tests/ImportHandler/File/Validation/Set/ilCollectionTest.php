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

namespace ILIAS\Export\Test\ImportHandler\File\Validation\Set;

use ILIAS\Export\ImportHandler\Validation\Set\Collection as ilFileValidationSetCollection;
use ILIAS\Export\ImportHandler\Validation\Set\Handler as ilFileValidationSetHandler;
use PHPUnit\Framework\TestCase;

class ilCollectionTest extends TestCase
{
    public function testSetCollection(): void
    {
        $set1 = $this->createMock(ilFileValidationSetHandler::class);
        $set2 = $this->createMock(ilFileValidationSetHandler::class);
        $set3 = $this->createMock(ilFileValidationSetHandler::class);
        $sets = [$set1, $set2, $set3];

        $collection = (new ilFileValidationSetCollection())
            ->withElement($set1)
            ->withElement($set2)
            ->withElement($set3);

        $this->assertCount(3, $collection);
        for ($i = 0; $i < 3; $i++) {
            $sets[$i] = $collection->toArray()[$i];
        }
    }
}
