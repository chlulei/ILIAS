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

namespace ILIAS\Export\Test\ImportHandler\File\Path\Comparison;

use ILIAS\Export\ImportHandler\Path\Comparison\Handler as ilFilePathComparison;
use ILIAS\Export\ImportHandler\Path\Comparison\Operator as ilFilePathComparisonOperator;
use PHPUnit\Framework\TestCase;

class ilHandlerTest extends TestCase
{
    protected function setUp(): void
    {

    }

    public function testComparison(): void
    {
        $comp1 = new ilFilePathComparison();
        $comp1 = $comp1
            ->withValue('Args')
            ->withOperator(ilFilePathComparisonOperator::EQUAL);
        $comp2 = $comp1
            ->withOperator(ilFilePathComparisonOperator::LOWER_EQUAL)
            ->withValue('');
        $comp3 = $comp1
            ->withOperator(ilFilePathComparisonOperator::GREATER)
            ->withValue('2');

        $this->assertEquals(
            ilFilePathComparisonOperator::toString(ilFilePathComparisonOperator::EQUAL) . 'Args',
            $comp1->toString()
        );

        $this->assertEquals(
            ilFilePathComparisonOperator::toString(ilFilePathComparisonOperator::LOWER_EQUAL),
            $comp2->toString()
        );

        $this->assertEquals(
            ilFilePathComparisonOperator::toString(ilFilePathComparisonOperator::GREATER) . '2',
            $comp3->toString()
        );
    }
}
