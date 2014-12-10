<?php
namespace EWW\Dpf\Controller;


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
 * DocumentFormController
 */
class DocumentFormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * documentRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\DocumentRepository
	 * @inject
	 */
	protected $documentRepository = NULL;
        
        
        /**
	 * documentTypeRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\DocumentTypeRepository
	 * @inject
	 */
	protected $documentTypeRepository = NULL;        


        /**
	 * metadataGroupRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\MetadataGroupRepository
	 * @inject
	 */
	protected $metadataGroupRepository = NULL;

        
         /**
	 * metadataObjectRepository
	 *
	 * @var \EWW\Dpf\Domain\Repository\MetadataObjectRepository
	 * @inject
	 */
	protected $metadataObjectRepository = NULL;
        
        
        /**
         * persistence manager
         *
         * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
         * @inject
         */
        protected $persistenceManager;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$documents = $this->documentRepository->findAll();
                
                $documentTypes = $this->documentTypeRepository->findAll();
                                
                
                $this->view->assign('listtype', $this->settings['listtype']);
                
                $this->view->assign('documentTypes', $documentTypes);                                
		$this->view->assign('documents', $documents);
	}

	/**
	 * action show
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $document
	 * @return void
	 */
	public function showAction(\EWW\Dpf\Domain\Model\Document $document) {
                                  
		$this->view->assign('document', $document);
	}

        
        
           //$this->forward('edit',NULL,NULL,array('document' => $document));
        
        /**
         * initialize newAction
         * 
         * @return void
         */
        public function initializeNewAction() {
          
          $requestArguments = $this->request->getArguments();   
                                                      
          if (array_key_exists('documentData', $requestArguments)) {            
            $documentData = $this->request->getArgument('documentData');  
            $formDataReader = $this->objectManager->get('EWW\Dpf\Helper\FormDataReader');
            $formDataReader->setFormData($documentData);
            $docForm = $formDataReader->getDocumentForm();
          } else {
            $docTypeUid = $this->request->getArgument('documentType');          
            $documentType = $this->documentTypeRepository->findByUid($docTypeUid);                
            $document = $this->objectManager->create('\\EWW\\Dpf\\Domain\\Model\\Document');                                             
            $mapper = new \EWW\Dpf\Helper\DocumentFormMapper(); 
            $docForm = $mapper->getDocumentForm($documentType,$document);
          }
          
          $requestArguments['newDocumentForm'] = $docForm;
          $this->request->setArguments($requestArguments);                       
        }
        
        
	/**
	 * action new
	 *
	 * @param \EWW\Dpf\Domain\Model\DocumentForm $newDocumentForm
	 * @ignorevalidation $newDocumentForm
	 * @return void
	 */
	public function newAction(\EWW\Dpf\Domain\Model\DocumentForm $newDocumentForm = NULL) {                      
                $this->view->assign('documentForm', $newDocumentForm);            
	}

        
      
        public function initializeCreateAction() {
        
            $requestArguments = $this->request->getArguments();                                                                 
        
            $documentData = $this->request->getArgument('documentData');
            
            //$documentType = $this->documentTypeRepository->findByUid($documentData['type']);
            //$newDoc = new \EWW\Dpf\Domain\Model\Document();
            //$newDoc->setDocumentType($documentType);
            //
            //$mapper = new \EWW\Dpf\Helper\DocumentFormMapper();
            //    
            //$newDocument = $mapper->getDocumentData($documentType,$documentData);
            
            $formDataReader = $this->objectManager->get('EWW\Dpf\Helper\FormDataReader');
            $formDataReader->setFormData($documentData);
            $docForm = $formDataReader->getDocumentForm();
            
            $requestArguments['newDocumentForm'] = $docForm;
            $this->request->setArguments($requestArguments);                                                   
        }
        
        
	/**
	 * action create
	 *
	 * @param \EWW\Dpf\Domain\Model\DocumentForm $newDocumentForm
	 * @return void
	 */
	public function createAction(\EWW\Dpf\Domain\Model\DocumentForm $newDocumentForm) {
                              
          $documentForm = $newDocumentForm;
                    
          foreach ($documentForm->getItems() as $page) {                          
              
            foreach ($page[0]->getItems() as $group) {              
             
              foreach ($group as $groupItem) {    
                
                $item = array();
                
                $uid = $groupItem->getUid();
                $metadataGroup = $this->metadataGroupRepository->findByUid($uid);                
                $groupMapping =  "/" .  trim($metadataGroup->getMapping()," /");          
                 
                              
                //$item = $this->readFormData($child, $group);            
                $item['mapping'] = $groupMapping;
                $item['groupUid'] = $uid;
                                
                foreach ($groupItem->getItems() as $field) {                                                       
                  foreach ($field as $fieldItem) {                      
                    $fieldUid = $fieldItem->getUid();
                    $metadataObject = $this->metadataObjectRepository->findByUid($fieldUid);                
                    $fieldMapping = trim($metadataObject->getMapping()," /");     
                   
                    $formField = array();
                    
                    $value = $fieldItem->getValue();
                    if ($value) {                      
                      $formField['mapping'] = $fieldMapping;
                      $formField['value'] = $value;
                      
                      if ( strpos($fieldMapping, "@") === 0) {
                        $item['attributes'][] = $formField;                     
                      } else {
                        $item['values'][] = $formField;
                      }
                    }                                                                                                                                                     
                  }                                                             
                }
                
                if (!key_exists('attributes', $item)) $item['attributes'] = array();
                if (!key_exists('values', $item)) $item['values'] = array();  
                
                $form[] = $item; 
                                                  
              }
                
            }
              
              
              
            
            
          }
          
          echo "<pre>";
          var_dump($form);
          echo "</pre>";
          
          die();
          
          //      $documentType = $this->documentTypeRepository->findByUid($documentData['type']);
          

          //      $newDoc = new \EWW\Dpf\Domain\Model\Document();
          //      $newDoc->setDocumentType($documentType);
          //      
          //      $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();
          //      
          //      $newDocument = $mapper->getDocumentData($documentType,$documentData);
          //                                    
          //      foreach ($newDocument['files'] as $tmpFile ) {
          //                                            
          //        $path = "uploads/tx_dpf";
          //        $fileName = $path."/".time()."_".rand()."_".$tmpFile['name']; 
          //        
          //        if (\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tmpFile['tmp_name'],$fileName) ) {                    
          //           $file['type'] = $mimeType = $tmpFile['type'];  
          //           $file['path'] = $fileName;
          //        
          //           $files[] = $file;
          //           
          //        }
          //                                                             
          //      }
          //      
          //      $newDocument['files'] = $files;
          //      
          //      
          //      $exporter = new \EWW\Dpf\Services\MetsExporter();                
          //      $exporter->buildModsFromForm($newDocument);
          //      
          //      $xml = $exporter->getMetsData();
          //      
          //      $title = $this->getTitleFromXmlData($xml);                                
          //      $newDoc->setTitle($title);                
          //      
          //      $newDoc->setXmlData($xml);                
          //      
          //      $this->documentRepository->add($newDoc);
                                
                $this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $document
	 * @ignorevalidation $document
	 * @return void
	 */
	public function editAction(\EWW\Dpf\Domain\Model\Document $document) {                               
                                      
                $documentType = $document->getDocumentType();                
                $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();               
                                
                //$documentForm = $mapper->getDocumentForm($documentType,$document);   
                
                $documentForm = $mapper->getDocumentForm($documentType,$document); 
                                       
		$this->view->assign('documentForm', $documentForm);                                                
	}

	/**
	 * action update
	 *
	 * @param array $documentData
	 * @return void
	 */
	public function updateAction(array $documentData) {
                    
             // $this->view->assign('debugData',$documentData); 
                                                                                      
                $documentType = $this->documentTypeRepository->findByUid($documentData['type']);
                $document = $this->documentRepository->findByUid($documentData['documentUid']);  
                $document->setDocumentType($documentType);
                
                $mapper = new \EWW\Dpf\Helper\DocumentFormMapper();
               
                $updateDocument = $mapper->getDocumentData($documentType,$documentData);
                                             
                //$validator = $this->objectManager->create('\EWW\Dpf\Helper\DocumentFormValidator');                
                //$validator->setDocumentType($documentType);
                //$validator->setFormData($documentData);
                //$docForm = $validator->validate();
                                               
                $formDataReader = $this->objectManager->get('EWW\Dpf\Helper\FormDataReader');
                $formDataReader->setFormData($documentData);
                //$docForm = $formDataReader->getDocumentForm();
                
               // $data = $formDataReader->getDocumentForm();
               // $this->view->assign('debugData', $data);
               
      /*          $this->controllerContext->getFlashMessageQueue()->enqueue(
  $this->objectManager->get(
    'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
    'Einige Felder sind nicht korrekt ausgefüllt.',
    'Speichern nicht möglich.',
    \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
    $storeInSession
  )
);
        */        
          // $this->view->assign('debugData', $updateDocument);
                                                          
                foreach ($updateDocument['files'] as $tmpFile ) {
                                                      
                  $path = "uploads/tx_dpf";
                  $fileName = $path."/".time()."_".rand()."_".$tmpFile['name']; 
                  
                  if (\TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tmpFile['tmp_name'],$fileName) ) {                    
                     $file['type'] = $mimeType = $tmpFile['type'];  
                     $file['path'] = $fileName;
                  
                     $files[] = $file;
                     
                  }
                                                                       
                }
              
                $updateDocument['files'] = $files;
                
                
               // $this->view->assign('debugData', $updateDocument);
                
                $exporter = new \EWW\Dpf\Services\MetsExporter();                
                $exporter->buildModsFromForm($updateDocument);
                
                $xml = $exporter->getMetsData();
	       // var_dump($xml); die();
               //  $this->view->assign('debugData', $xml);
                     
              // $this->view->assign('debugData',$updateDocument);  
                $title = $this->getTitleFromXmlData($xml);                                
                $document->setTitle($title);                
               
                $document->setXmlData($xml);                
                               
                $this->documentRepository->update($document);
                                                    
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \EWW\Dpf\Domain\Model\Document $document
	 * @return void
	 */
	public function deleteAction(\EWW\Dpf\Domain\Model\Document $document) {
		//$this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See <a href="http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain" target="_blank">Wiki</a>', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		$this->documentRepository->remove($document);
		$this->redirect('list');
	}
        
                         
        /**
         *
         * @param integer $pageUid
         * @param integer $groupUid
         * @param integer $groupIndex
         * @return void
         */
        public function ajaxGroupAction($pageUid, $groupUid, $groupIndex) {
                             
           $group = $this->metadataGroupRepository->findByUid($groupUid);

           //$groupItem = array();

           $groupItem = new \EWW\Dpf\Domain\Model\DocumentFormGroup();
           
           
           foreach ($group->getMetadataObject() as $object) {
                    
              $field = new \EWW\Dpf\Domain\Model\DocumentFormField();

              $field->setUid($object->getUid());
              $field->setDisplayName($object->getDisplayName());
              $field->setMandatory($object->getMandatory());
              $field->setInputField($object->getInputField());
              $field->setMaxIteration($object->getMaxIteration());
              $field->setValue("");

              $groupItem->addItem($field);                       
           }
           
                                                    
           $this->view->assign('formPageUid',$pageUid);
           $this->view->assign('formGroupUid',$groupUid);
           $this->view->assign('formGroupDisplayName',$group->getDisplayName());
           $this->view->assign('groupIndex',$groupIndex);
           $this->view->assign('groupItem',$groupItem);           
        }

        
        /**
         *
         * @param integer $pageUid
         * @param integer $groupUid
         * @param integer $groupIndex
         * @param integer $fieldUid
         * @param integer $fieldIndex
         * @return void
         */
        public function ajaxFieldAction($pageUid, $groupUid, $groupIndex, $fieldUid, $fieldIndex) {

           $field = $this->metadataObjectRepository->findByUid($fieldUid);         
                    
           $fieldItem = new \EWW\Dpf\Domain\Model\DocumentFormField();

           $fieldItem->setUid($field->getUid());
           $fieldItem->setDisplayName($field->getDisplayName());
           $fieldItem->setMandatory($field->getMandatory());
           $fieldItem->setInputField($field->getInputField());
           $fieldItem->setMaxIteration($field->getMaxIteration());
           $fieldItem->setValue("");                      
           
           $this->view->assign('formPageUid',$pageUid);
           $this->view->assign('formGroupUid',$groupUid);           
           $this->view->assign('groupIndex',$groupIndex);                                           
        //   $this->view->assign('formField',$formField);   
           $this->view->assign('fieldIndex',$fieldIndex);   
           $this->view->assign('fieldItem',$fieldItem);        
          // $this->view->assign('countries',);           
        }

        /**
         *        
         * @param integer $groupIndex
         * @return void
         */
        public function ajaxFileGroupAction(integer $groupIndex) {            
           $this->view->assign('groupIndex',$groupIndex);
           $this->view->assign('displayName','Sekundärdatei');           
        }
        
        
        /**
         * 
         * @param string $xml
         * @return void
         */        
        protected function getTitleFromXmlData($xml) {          
            $metsDom = new \DOMDocument();
            $metsDom->loadXML($xml);
            $metsXpath = new \DOMXPath($metsDom);  
            $metsXpath->registerNamespace("mods", "http://www.loc.gov/mods/v3");        
            $modsNodes = $metsXpath->query("/mets:mets/mets:dmdSec/mets:mdWrap/mets:xmlData/mods:mods");

            $modsDom = new \DOMDocument();
            $modsDom->loadXML($metsDom->saveXML($modsNodes->item(0)));    

            $modsXpath = new \DOMXPath($modsDom);     
            $titleNode = $modsXpath->query("/mods:mods/mods:titleInfo/mods:title");

            return $titleNode->item(0)->nodeValue;                              
        }
              
        
        
        
        

    /**
     * Initialize action method validators
     *
     * @return void
     */
  /*  protected function initializeActionMethodValidators() {
            parent::initializeActionMethodValidators();
                                    
            foreach ($this->arguments as $argument) {
                    // @var  Tx_Extbase_MVC_Controller_Argument $argument
                    if ($argument->getName() == 'documentData' && $this->actionMethodName == 'updateAction') {
                            // @var Tx_Extbase_Validation_Validator_ConjunctionValidator $validator
                            $validator = $argument->getValidator();
                            $requestArguments = $this->request->getArguments();

                            $ewwValidator = $this->objectManager->get('\EWW\Dpf\Validation\DocumentFormValidator');
                           $ewwValidator->setOptions(
                                    array(
                                            'value' => 'test'
                                    )
                            );
                            $validator->addValidator($ewwValidator);
                    }
            }
    }
    */
        
    public function initializeAction() {
      parent::initializeAction();
      
       $requestArguments = $this->request->getArguments();                
       
       if ($requestArguments['cancel']) {         
         $this->redirect('list');         
       }
    }    

}