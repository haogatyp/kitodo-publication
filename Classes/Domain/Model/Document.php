<?php
namespace EWW\Dpf\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Document
 */
class Document extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

  
        /**
	 * crdate
	 *
	 * @var DateTime
	 */
	protected $crdate;        
  
        /**
	 * title
	 *
	 * @var string
	 */
	protected $title = '';
  
	/**
	 * xmlData
	 *
	 * @var string
	 */
	protected $xmlData = '';

	/**
	 * documentType
	 *
	 * @var \EWW\Dpf\Domain\Model\DocumentType
	 */
	protected $documentType = NULL;
                
        /**
         * objectIdentifier
         * 
         * @var integer         
         */
        protected $objectIdentifier;      
                
        /**
         * transferStatus
         * 
         * @var string
         */
        protected $transferStatus;               
                
        /**
         *  transferDate
         * 
         *  @var integer
         */
        protected $transferDate;    
        
        /**
         * transferErrorCode
         * 
         * @var integer
         */
        protected $transferErrorCode;
        
        /**
         * transferResponse
         * 
         * @var string
         */
	protected $transferResponse;
                
        /**
         * transferHttpStatus
         * 
         * @var integer
         */        
	protected $transferHttpStatus;	
        
        
        /**
	 * file
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Eww\Dpf\Domain\Model\File>
	 * @cascade remove
	 */
	protected $file = NULL;
        
                      
        const TRANSFER_ERROR = "ERROR";
        
        const TRANSFER_QUEUED = "QUEUED";
                     
        const TRANSFER_SENT = "SENT";
               
        /**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}
                
        /**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
        
	/**
	 * Returns the xmlData
	 *
	 * @return string $xmlData
	 */
	public function getXmlData() {
		return $this->xmlData;
	}

	/**
	 * Sets the xmlData
	 *
	 * @param string $xmlData
	 * @return void
	 */
	public function setXmlData($xmlData) {
		$this->xmlData = $xmlData;
	}

	/**
	 * Returns the documentType
	 *
	 * @return \EWW\Dpf\Domain\Model\DocumentType $documentType
	 */
	public function getDocumentType() {
		return $this->documentType;
	}

	/**
	 * Sets the documentType
	 *
	 * @param \EWW\Dpf\Domain\Model\DocumentType $documentType
	 * @return void
	 */
	public function setDocumentType(\EWW\Dpf\Domain\Model\DocumentType $documentType) {
		$this->documentType = $documentType;
	}
        
        /**
         * Returns the crdate
         * 
         * @return DateTime
         */        
        public function getCrdate() {
          return $this->crdate;
        }
        
        /**
         * Returns the objectIdentifier
         * 
         * @return string
         */
        public function getObjectIdentifier() {
          return $this->objectIdentifier;          
        }
        
        /**
         * Sets the objectIdentifier
         * 
         * @param string $objectIdentifier
         * @return void
         */
        public function setObjectIdentifier($objectIdentifier) {
          $this->objectIdentifier = $objectIdentifier;          
        }
                               
        /**         
         * Returns the transferStatus
         * @var string
         */
        public function getTransferStatus() {
          return $this->transferStatus; 
        }               
                
        /**         
         * Sets the transferStatus
         * 
         * @param string
         * @return void
         */
        public function setTransferStatus($transferStatus) {
          $this->transferStatus = $transferStatus; 
        }               
                
        /**         
         * Returns the transferDate
         * 
         * @var integer
         */
        public function getTransferDate() {
          return $this->transferDate; 
        }               
                
        /**         
         * Sets the transferDate 
         * 
         * @param integer $transferDate
         * @return void
         */
        public function setTransferDate($transferDate) {
          $this->transferDate = $transferDate; 
        }               
                
        /**         
         * Returns the transferErrorCode
         * 
         * @var integer
         */
        public function getTransferErrorCode() {
          return $this->transferErrorCode; 
        }               
                
        /**         
         * Sets the transferErrorCode
         * 
         * @param integer $transferErrorCode
         * @return void
         */
        public function setTransferErrorCode($transferErrorCode) {
          $this->transferErrorCode = $transferErrorCode; 
        }               
                                 
        /**         
         * Returns the transferResponse
         * 
         * @var string
         */
        public function getTransferResponse() {
          return $this->transferResponse; 
        }               
                
        /**         
         * Sets the transferResponse
         * 
         * @param string $transferResponse
         * @return void
         */
        public function setTransferResponse($transferResponse) {
          $this->transferResponse = $transferResponse; 
        }               
        
        /**         
         * Returns the transferHttpStatus
         * 
         * @var integer
         */
        public function getTransferHttpStatus() {
          return $this->transferHttpStatus; 
        }               
                
        /**         
         * Sets the transferHttpStatus
         * 
         * @param integer $transferHttpStatus
         * @return void
         */
        public function setTransferHttpStatus($transferHttpStatus) {
          $this->transferHttpStatus = $transferHttpStatus; 
        } 
        
        
        /**
	 * Adds a File
	 *
	 * @param \Eww\Dpf\Domain\Model\File $file
	 * @return void
	 */
	public function addFile(\Eww\Dpf\Domain\Model\File $file) {
		$this->file->attach($file);
	}

	/**
	 * Removes a File
	 *
	 * @param \Eww\Dpf\Domain\Model\File $fileToRemove The File to be removed
	 * @return void
	 */
	public function removeFile(\Eww\Dpf\Domain\Model\File $fileToRemove) {
		$this->file->detach($fileToRemove);
	}

	/**
	 * Returns the file
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Eww\Dpf\Domain\Model\File> $file
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Sets the file
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Eww\Dpf\Domain\Model\File> $file
	 * @return void
	 */
	public function setFile(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $file) {
		$this->file = $file;
	}
                                                            
}