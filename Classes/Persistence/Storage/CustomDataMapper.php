<?php
namespace EWW\Dpf\Persistence\Storage;

class CustomDataMapper extends \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper {

    /**
     * Maps a single row on an object of the given class
     *
     * @param string $className The name of the target class
     * @param array $row A single array with field_name => value pairs
     * @return object An object of the given class
     */
    protected function mapSingleRow($className, array $row) {
        $uid = isset($row['_LOCALIZED_UID']) ? $row['_LOCALIZED_UID'] : $row['uid'];

        $session = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class)->get(\TYPO3\CMS\Extbase\Persistence\Generic\Session::class);
        if ($session->hasIdentifier($uid, $className)) {
            $object = $session->getObjectByIdentifier($uid, $className);
        } else {
            $object = $this->createEmptyObject($className);
            $session->registerObject($object, $uid);
            $this->thawProperties($object, $row);
            $object->_memorizeCleanState();
            $this->persistenceSession->registerReconstitutedEntity($object);
        }

        return $object;
    }
}
