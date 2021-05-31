<?php
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\NullTagsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\Factories\NoteFactory;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NullCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\NoteType\CommonNote;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\TagModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ .'/amo/boot.php';
$name = $_POST['name'];
$phone = $_POST['tel'];
$roistatVisitId = array_key_exists('roistat_visit', $_COOKIE) ? $_COOKIE['roistat_visit'] : 'nocookie';

$address = 'rmfighht@yandex.ru';
$sub = "Заявка с сайта ".$_SERVER['HTTP_HOST']."";
$mes = "\n Код $roistatVisitId \n IP: ".$_SERVER["REMOTE_ADDR"]."\n $from";
$verify = mail ($address,$sub,$mes,"Content-type:text/plain; charset = UTF-8\r\nFrom:zakaz");
$URL="form-ok.php?name=$name&phone=$phone";
if($_SERVER['REQUEST_METHOD'] == "POST"){
// $text = $message;
$accessToken = getToken();
$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(
        function (AccessTokenInterface $accessToken, string $baseDomain) {
            saveToken(
                [
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $baseDomain,
                ]
            );
        }
    );


    $title = "Заявка с сайта quiz1";
    $leadsService = $apiClient->leads();
    
    $lead = new LeadModel();
    $lead->setName($title);
    
    $leadCustomFieldsValues = new CustomFieldsValuesCollection();
    $lead->setTags((new TagsCollection())
        ->add(
            (new TagModel())
                ->setName('quiz1')
        )
    );
    $roi = new TextCustomFieldValuesModel();
    $roi->setFieldId(112151);
    $roi->setValues(
        (new TextCustomFieldValueCollection())
            ->add((new TextCustomFieldValueModel())->setValue($roistatVisitId))
    );
    $lead->setCustomFieldsValues($leadCustomFieldsValues->add($roi));
    $leadsCollection = new LeadsCollection();
    $leadsCollection->add($lead);
    try {
        $lead = $leadsService->addOne($lead);
        // print_r($lead);
    
    } catch (AmoCRMApiException $e) {
        
        printError($e);
        die;
    }
    $filter = new ContactsFilter();
    $phone	= str_replace(array('(', ')', ' ', '-'), '', $phone);
    $filter->setQuery($phone);

    try {
        $contacts = $apiClient->contacts()->get($filter);


    } catch (AmoCRMApiException $e) {
        // printError($e);
        // die;
    }
    if(!empty($contacts)){
        foreach ($contacts as $contact) {
            $links = new LinksCollection();
            $links->add($lead);
            try {
                $apiClient->contacts()->link($contact, $links);
            } catch (AmoCRMApiException $e) {
                printError($e);
                die;
            }
        }
    }else{
        $ContactsCollection = new ContactsCollection();
        $contact = new ContactModel();
        $contact->setName($name);
        $contactCustomFields = new CustomFieldsValuesCollection();
        $phoneFieldValueModel = new MultitextCustomFieldValuesModel();
        $phoneFieldValueModel->setFieldId(34235);
        $phoneFieldValueModel->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add((new MultitextCustomFieldValueModel())->setEnum('WORK')->setValue($phone))
        );
        // $emailFieldValueModel = new MultitextCustomFieldValuesModel();
        // $emailFieldValueModel->setFieldId(404193);
        // $emailFieldValueModel->setValues(
        //     (new MultitextCustomFieldValueCollection())
        //         ->add((new MultitextCustomFieldValueModel())->setEnum('WORK')->setValue($mail))
        // );
    
        $contact->setCustomFieldsValues($contactCustomFields->add($phoneFieldValueModel));
        // $contact->setCustomFieldsValues($contactCustomFields->add($emailFieldValueModel));
        $ContactsCollection->add($contact);
    
        try {
            $contactModel = $apiClient->contacts()->addOne($contact);
            print_r($contactModel);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }
        $links = new LinksCollection();
        $links->add($lead);
        try {
            $apiClient->contacts()->link($contactModel, $links);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }
    }

    if($text != ''){
        $notesCollection = new NotesCollection();
        $serviceMessageNote = new CommonNote();
        $serviceMessageNote->setEntityId($lead->id)
            ->setText($text);
            // ->setService('Api Library')
            // ->setCreatedBy(0);
        $notesCollection->add($serviceMessageNote);
        try {
            $leadNotesService = $apiClient->notes(EntityTypesInterface::LEADS);
            $notesCollection = $leadNotesService->add($notesCollection);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }
    }
}
if($verify == 'true'){ 
echo("<meta http-equiv=\"REFRESH\" content=\"0; URL='$URL'\">");
} else{ 
echo "<p>Произошла ошибка попробуйте заполнить форму повторно!</p>"; 
}
