<?php

/**
 * @copyright Copyright © ${commentsYear} ${CommentsCompanyName}. All rights reserved.
 * @author    ${commentsUserEmail}
 */

namespace ${Vendorname}\${Modulename}\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ${Entityname}Interface extends ExtensibleDataInterface
{
    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return \${Vendorname}\${Modulename}\Api\Data\${Entityname}ExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \${Vendorname}\${Modulename}\Api\Data\${Entityname}ExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\${Vendorname}\${Modulename}\Api\Data\${Entityname}ExtensionInterface $extensionAttributes);
}
