<?php
namespace EWW\Dpf\Domain\Repository;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * The repository for Clients
 */
class ClientRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * findAllByPid
     *
     * @return
     */
    public function findAllByPid($pid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->equals('pid', $pid));
        return $query->execute();
    }


    public function getLanguage() {

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
