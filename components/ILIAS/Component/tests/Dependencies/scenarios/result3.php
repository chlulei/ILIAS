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

$null_dic = new ILIAS\Component\Dependencies\NullDIC();


$component_0 = new ILIAS\Component\Tests\Dependencies\Scenario3\ComponentA();

$implement_0 = new ILIAS\Component\Dependencies\RenamingDIC(new Pimple\Container());
$use = new Pimple\Container();
$contribute_0 = new ILIAS\Component\Dependencies\RenamingDIC(new Pimple\Container());
$seek = new Pimple\Container();
$provide_0 = new Pimple\Container();
$pull = new Pimple\Container();
$internal = new Pimple\Container();

$component_0->init($null_dic, $implement_0, $use, $contribute_0, $seek, $provide_0, $pull, $internal);


$component_1 = new ILIAS\Component\Tests\Dependencies\Scenario3\ComponentB();

$implement_1 = new ILIAS\Component\Dependencies\RenamingDIC(new Pimple\Container());
$use = new Pimple\Container();
$use[ILIAS\Component\Tests\Dependencies\Scenario3\Service::class] = fn() => $implement_0[ILIAS\Component\Tests\Dependencies\Scenario3\Service::class . "_0"];
$contribute_1 = new ILIAS\Component\Dependencies\RenamingDIC(new Pimple\Container());
$seek = new Pimple\Container();
$provide_1 = new Pimple\Container();
$pull = new Pimple\Container();
$internal = new Pimple\Container();

$component_1->init($null_dic, $implement_1, $use, $contribute_1, $seek, $provide_1, $pull, $internal);
