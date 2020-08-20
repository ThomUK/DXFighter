<?php

/**
 * Created by PhpStorm.
 * User: jpietler
 * Date: 13.02.20
 * Time: 20:57
 *
 * Documentation https://www.autodesk.com/techpubs/autocad/acad2000/dxf/insert_dxf_06.htm
 */

namespace DXFighter\lib;

/**
 * Class Circle
 * @package DXFighter\lib
 */
class Insert extends Entity
{
    protected $name;
    protected $point;
    protected $rotationAngle;

    /**
     * Insert constructor.
     * @param string $pointer Pointer to the block_record's handle
     * @param string $name
     * @param array $point
     * @param float $rotationAngle
     */
    function __construct(
        $pointer,
        $name,
        $point = [0, 0, 0],
        $rotationAngle = 0
    ) {
        parent::__construct();
        $this->entityType = 'insert';
        $this->pointer = $pointer;
        $this->point = $point;
        $this->name = $name;
        $this->rotationAngle = $rotationAngle;
    }

    /**
     * Public function to move an Insert entity
     * @param array $move vector to move the entity with
     */
    public function move($move)
    {
        $this->movePoint($this->point, $move);
    }

    /**
     * Public function to render an entity, returns a string representation of
     * the entity.
     * @return string
     */
    public function render()
    {
        $output = parent::render();
        array_push($output, 100, 'AcDbBlockReference');
        array_push($output, 2, strtoupper($this->name));
        array_push($output, $this->point($this->point));
        array_push($output, 50, $this->rotationAngle);
        return implode(PHP_EOL, $output);
    }
}
