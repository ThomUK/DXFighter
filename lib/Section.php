<?php

/**
 * Created by PhpStorm.
 * User: jpietler
 * Date: 04.02.16
 * Time: 19:23
 *
 * Documentation http://www.autodesk.com/techpubs/autocad/acad2000/dxf/general_dxf_file_structure_dxf_aa.htm
 */

namespace DXFighter\lib;

/**
 * Class Section
 * @package DXFighter\lib
 *
 * Every DXF file is build up of multiple sections which contain
 * differnt parts of the DXF definition. Some of them are mandatory, these are:
 * header, classes, tables, blocks, entities, objects and thumbnailimage.
 */
class Section
{
    protected $sectionName;
    protected $items = [];
    protected $itemNames = [];
    protected $itemHandles = [];

    /**
     * Section constructor.
     * @param $name
     * @param array $items
     */
    function __construct($sectionName, $items = [])
    {
        $this->sectionName = $sectionName;
        $this->items = $items;
    }

    /**
     * Adds an Item to the items list
     *
     * @param BasicObject $item
     */
    public function addItem(BasicObject $item)
    {
        $name = strtoupper($item->getName());
        $handle = $item->getHandle();
        if (!in_array($handle, $this->itemHandles)) {
            $this->itemHandles[] = $handle;
            $this->itemNames[] = $name;
            $this->items[] = $item;
        } elseif ($this->sectionName == 'tables') {
            foreach ($this->items as $existing) {
                if (strtoupper($existing->getName()) == $name) {
                    $entries = $item->getEntries();
                    foreach ($entries as $entry) {
                        $existing->addEntry($entry);
                    }
                }
            }
        }
    }

    /**
     * Adds an array of Items to the items list
     *
     * @param array $items
     */
    public function addMultipleItems($items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * Public function to render an entity, returns a string representation of
     * the entity.
     * @return string
     */
    public function render()
    {
        $output = array();
        array_push($output, 0, "SECTION");
        array_push($output, 2, strtoupper($this->sectionName));
        foreach ($this->items as $item) {
            array_push($output, $item->render());
        }
        array_push($output, 0, "ENDSEC");
        return implode(PHP_EOL, $output);
    }

    /**
     * Outputs an array representation of the Section
     * TODO: Find a good way to export the Section
     *
     * @return array
     */
    public function toArray()
    {
        return [];
    }
}
