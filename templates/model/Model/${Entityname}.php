<?php

/**
 * @copyright Copyright © ${commentsYear} ${CommentsCompanyName}. All rights reserved.
 * @author    ${commentsUserEmail}
 */

namespace ${Vendorname}\${Modulename}\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use ${Vendorname}\${Modulename}\Api\Data\${Entityname}ExtensionInterface;
use ${Vendorname}\${Modulename}\Api\Data\${Entityname}Interface;

class ${Entityname} extends AbstractExtensibleModel implements ${Entityname}Interface
{
    public const NAME = 'name';

    public const ID = 'entity_id';
    /**
     * Initialize resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('${Vendorname}\${Modulename}\Model\ResourceModel\${Entityname}');
    }

    public function getName()
    {
        return $this->_getData(self::NAME);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getId()
    {
        return $this->_getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(${Entityname}ExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }
}
