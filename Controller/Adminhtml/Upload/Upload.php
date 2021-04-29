<?php

namespace Excellence\Storebase\Controller\Adminhtml\Upload;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;


class Upload extends \Magento\Backend\App\Action
{
    /**
    * @var \Magento\Framework\Image\AdapterFactory
    */
    protected $adapterFactory;
    /**
    * @var \Magento\MediaStorage\Model\File\UploaderFactory
    */
    protected $uploader;
    /**
    * @var \Magento\Framework\Filesystem
    */
    protected $filesystem;
    protected $storeManager;

    protected $resultPageFactory = false;
    protected $resultRedirectFactory = false;
    protected $_slideFactory = false;
    protected $_fileUploaderFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\ StoreManagerInterface $storeManager,
        \Excellence\Storebase\Model\StorebaseFactory $storebaseFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_storebaseFactory = $storebaseFactory;

        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->resultRedirect = $this->resultRedirectFactory->create();
    }

    public function execute(){

        $storeBaseModel = $this->_storebaseFactory->create();
        $errors = array();
    
        if (isset($_FILES['file']) && isset($_FILES['file']['name']) && strlen($_FILES['file']['name'])) {
            
            /*
            * Save image upload
            */
            try {

                $base_media_path = 'geoipImport/';
                $uploader = $this->uploader->create(
                    ['fileId' => 'file']
                );
                $uploader->setAllowedExtensions(['csv']);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath($base_media_path)
                );
               
                $data['file'] = $base_media_path.$result['file'];
                $filename = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$data['file'];
                $file = fopen($filename, 'r');
                $all_rows = array();
                $header = null;
                while ($row = fgetcsv($file)) {
                    if ($header === null) {
                        $header = $row;
                        continue;
                    }
                    $all_rows[] = array_combine($header, $row);
                }
                $count = 1;
                foreach ($all_rows as $row) {
                    $error = '';
                    $storeBaseModel->setStoreView(0);
                    if(array_key_exists('Business Name', $row)){
                        $storeBaseModel->setStoreName($row['Business Name']);
                    }
                    else{
                        $error .= __('Store Name,');
                    }
                        
                    $i = 1;
                    $address = '';
                    while ($i<100) {
                        $key = 'Address line '.$i;
                        if(array_key_exists($key, $row)){
                            if(isset($row[$key])){
                                $address .= $row[$key];
                            }
                            $i++;
                        }
                        else{
                            break;
                        }
                    }
                    $storeBaseModel->setStoreAddress($address);
                    if(array_key_exists('Primary phone', $row)){
                        $storeBaseModel->setNumber($row['Primary phone']);
                    }
                    else{
                        $error .= __('Phone Number,');
                    }
                    if(array_key_exists('Locality', $row)){
                        $storeBaseModel->setCity($row['Locality']);
                    }
                    else{
                        $error .= __('City,');
                    }
                    if(array_key_exists('Postcode', $row)){
                        $storeBaseModel->setZipcode($row['Postcode']);
                    }
                    else{
                        $error .= __('Zipcode,');
                    }
                    if(array_key_exists('Country', $row)){
                        $storeBaseModel->setCountry($row['Country']);
                    }
                    else{
                        $error .= __('Country,');
                    }
                    if(array_key_exists('latitude', $row)){
                        $storeBaseModel->setLatitude('25.4454');
                    }
                    else{
                        $error .= __('Latitude,');
                    }    
                        
                    if(array_key_exists('longitude', $row)){
                        $storeBaseModel->setLongitude('78.565');
                    }
                    else{
                        $error .= __('Longitude,');
                    }

                    $storeBaseModel->setPosition(0);
                    $storeBaseModel->setStatus(1);
                    // Check Sunday
                    $time = '';
                    $timeRange = '{';
                    if(array_key_exists('Sunday hours', $row) && !empty($row['Sunday hours'])){
                        $storeBaseModel->setSunday(2);
                        $temp = explode('-', $row['Sunday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "SUNDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"sunday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setSunday(0);
                    }
                    // Check Monday
                    if(array_key_exists('Monday hours', $row) && !empty($row['Monday hours'])){
                        $storeBaseModel->setMonday(2);
                        $temp = explode('-', $row['Monday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "MONDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"monday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setMonday(0);
                    }
                    // Check Tuesday
                    if(array_key_exists('Tuesday hours', $row) && !empty($row['Tuesday hours'])){
                        $storeBaseModel->setTuesday(2);
                        $temp = explode('-', $row['Tuesday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "TUESDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"tuesday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setTuesday(0);
                    }
                    // Check Wednesday
                    if(array_key_exists('Wednesday hours', $row) && !empty($row['Wednesday hours'])){
                        $storeBaseModel->setWednesday(2);
                        $temp = explode('-', $row['Wednesday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "WEDNESDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"wednesday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setWednesday(0);
                    }
                    // Check Thursday
                    if(array_key_exists('Thursday hours', $row) && !empty($row['Thursday hours'])){
                        $storeBaseModel->setThursday(2);
                        $temp = explode('-', $row['Thursday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "THURSDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"thursday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setThursday(0);
                    }
                    // Check Friday
                    if(array_key_exists('Friday hours', $row) && !empty($row['Friday hours'])){
                        $storeBaseModel->setFriday(2);
                        $temp = explode('-', $row['Friday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "FRIDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"friday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setFriday(0);
                    }
                    // Check Saturday
                    if(array_key_exists('Saturday hours', $row) && !empty($row['Saturday hours'])){
                        $storeBaseModel->setSaturday(2);
                        $temp = explode('-', $row['Saturday hours']);
                        $formattedTime = $temp[0]." AM - ".$temp[1]." PM".' ';
                        $time .= "SATURDAY=> ".$formattedTime."<br>";
                        $timeRange .= '"saturday":"'.$formattedTime.'",';
                    } else{
                        $storeBaseModel->setSaturday(0);
                    }
                    $timeRange = rtrim($timeRange,',');
                    $timeRange .= "}";
                    $storeBaseModel->setTime($time);
                    $storeBaseModel->setTimeRange($timeRange);
                    $storeBaseModel->save();
                    if(strlen($error) > 0){
                        $errors[] = ["Row ".$count, $error];
                    }
                }
                
                // Error Table
                if($this->arrayToTable($errors)){
                    $this->messageManager->addError(__('Some Fields are missing')."<br>".$this->arrayToTable($errors));
                }

                $this->messageManager->addSuccess(__('File Uploaded and Imported.'));

                
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
        $this->resultRedirect->setPath('storebase/geoipimport/import');
        return $this->resultRedirect;
     
    }
    public function arrayToTable($errors) {
        if(count($errors) > 0){
            $html = "<table border=1; style='border-collapse: collapse;'>
                    <thead>
                    <th>Row</th>
                    <th>Missing Fields</th>
                    </thead>
                    <tbody>";
            foreach ($errors as $error) {
                $html .= "<tr><td>".$error[0]."</td><td>".$error[1]."</td></tr>";
            }
            $html .= "</tbody></table>";
            return $html;
        }
        return false;
    }
}
