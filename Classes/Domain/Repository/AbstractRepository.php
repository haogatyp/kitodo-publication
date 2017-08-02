<?php
namespace EWW\Dpf\Domain\Repository;

abstract class AbstractRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    protected $defaultOrderings = array(
        'displayName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    );

    public function initializeObject()
    {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\Object\\ObjectManager');
        $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
        $frameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $storagePid = array($frameworkConfiguration['persistence']['storagePid']);

        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectSysLanguage(TRUE);

        $lang = $this->getLanguage();
        //die($lang);

        $querySettings->setSysLanguageUid($lang);
        $querySettings->setRespectStoragePage(TRUE);
        $querySettings->setStoragePageIds($storagePid);
        $this->setDefaultQuerySettings($querySettings);
    }


    protected function getLanguage() {

        $lang = 'EN';

        if (TYPO3_MODE === 'FE') {
            if (isset($GLOBALS['TSFE']->config['config']['language'])) {
                $lang = strtoupper($GLOBALS['TSFE']->config['config']['language']);
            }
        } elseif (strlen($GLOBALS['BE_USER']->uc['lang']) > 0) {
            $lang = strtoupper($GLOBALS['BE_USER']->uc['lang']);
        }

        $query = $this->createQuery();
        $query->statement("SELECT l.uid FROM sys_language as l,(SELECT * FROM static_languages WHERE lg_iso_2 = '$lang') as sl WHERE static_lang_isocode = sl.uid");
        $result = $query->execute(true);

        if (sizeof($result) > 0) {
            return $result[0]['uid'];
        }

        return 0;
    }

}
