<?php

/**
 * Interface ilCtrlContextInterface
 *
 * @author Thibeau Fuhrer <thf@studer-raimann.ch>
 */
interface ilCtrlContextInterface
{
    /**
     * Adopts context properties from the according ones
     * delivered by the current request.
     */
    public function adoptRequestParameters() : void;

    /**
     * Returns whether this context is asynchronous or not.
     *
     * @return bool
     */
    public function isAsync() : bool;

    /**
     * Returns the path of this context.
     *
     * @return ilCtrlPathInterface
     */
    public function getPath() : ilCtrlPathInterface;

    /**
     * Sets the baseclass of the current context.
     *
     * @param string $base_class
     * @return ilCtrlContextInterface
     */
    public function setBaseClass(string $base_class) : ilCtrlContextInterface;

    /**
     * Returns the baseclass the current context.
     *
     * @return string|null
     */
    public function getBaseClass() : ?string;

    /**
     * Sets the target script of this context (usually ilias.php).
     *
     * @param string $target_script
     * @return ilCtrlContextInterface
     */
    public function setTargetScript(string $target_script) : ilCtrlContextInterface;

    /**
     * Returns the target script of this context.
     *
     * @return string
     */
    public function getTargetScript() : string;

    /**
     * Sets the current contexts command class.
     *
     * @param string $cmd_class
     * @return ilCtrlContextInterface
     */
    public function setCmdClass(string $cmd_class) : ilCtrlContextInterface;

    /**
     * Returns the command class of this context.
     *
     * @return string|null
     */
    public function getCmdClass() : ?string;

    /**
     * Sets the command which the current command- or baseclass
     * should perform.
     *
     * @param string $cmd
     * @return self
     */
    public function setCmd(string $cmd) : self;

    /**
     * Returns the command which the current command- or baseclass
     * should perform.
     *
     * @return string|null
     */
    public function getCmd() : ?string;

    /**
     * Sets the object type of the current context.
     *
     * @param string $obj_type
     * @return ilCtrlContextInterface
     */
    public function setObjType(string $obj_type) : ilCtrlContextInterface;

    /**
     * Returns the object type of the current context.
     *
     * @return string|null
     */
    public function getObjType() : ?string;

    /**
     * Sets the object id of the current context.
     *
     * @param int $obj_id
     * @return ilCtrlContextInterface
     */
    public function setObjId(int $obj_id) : ilCtrlContextInterface;

    /**
     * Returns the object id of the current context.
     *
     * @return int|null
     */
    public function getObjId() : ?int;
}