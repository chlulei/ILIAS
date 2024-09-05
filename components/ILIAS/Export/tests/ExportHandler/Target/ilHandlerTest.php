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

namespace Test\ExportHandler\Target;

use PHPUnit\Framework\TestCase;
use ILIAS\Export\ExportHandler\Target\ilHandler as ilExportHandlerTarget;

class ilHandlerTest extends TestCase
{
    public function testExportHandlerTarget(): void
    {
        $object_ids = array_map(function (string $id) {return $id + rand(0, 20); }, [0, 0, 0]);
        $type = "myType";
        $component = "componentcomponent";
        $class_name = "classclass";
        $v = explode(".", ILIAS_VERSION_NUMERIC);
        $target_release = "2000.20";
        $default_target_release = $v[0] . "." . $v[1] . ".0";
        $target_1 = (new ilExportHandlerTarget())
            ->withType($type)
            ->withComponent($component)
            ->withClassname($class_name)
            ->withTargetRelease("")
            ->withObjectIds($object_ids);
        $target_2 = $target_1->withTargetRelease($target_release);
        $this->assertEquals($type, $target_1->getType());
        $this->assertEquals($component, $target_1->getComponent());
        $this->assertEquals($class_name, $target_1->getClassname());
        $this->assertEquals($default_target_release, $target_1->getTargetRelease());
        $this->assertEquals($target_release, $target_2->getTargetRelease());
        $this->assertEmpty(array_diff($object_ids, $target_1->getObjectIds()));
    }
}
