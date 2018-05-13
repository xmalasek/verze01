<?php

namespace AdminModule;

use Nette;
use Nette\Application\Responses\JsonResponse;
use Nette\Utils\Json;
use Tracy\Debugger;

class EletricPresenter extends BasePresenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->database = $database;

    }

    public function renderDefault()
    {
        $this->template->eletric = $this->database->table('eletric');
    }

    public function renderShow()
    {
        $this->template->eletric = $this->database->table('eletric');


    }

    public function renderAdd()
    {



    }



//    public function actionAdd(array $items)
//    {
//        $this['editEletricForm']->setDefaults([
//
//            'lat' => $items->lat,
//            'lng' => $items->lng
//
//        ]);
//
//
//    }


    public function renderFault()
    {


    }


    public function actionFault($id){

        $this['insertFaultForm']->setDefaults([
            'id_device' => $id
        ]);

    }


    public function renderInfo($id){

        $device = $this->database->table('eletric')->where('id_eletric', $id);

        if (!$device) {
            $this->error('Příspěvek nebyl nalezen');
            $this->redirect('Eletric:default');
        }else{
            $this->template->eletric = $device;
        }
    }

//    public function handleAdd() {
//
//        $lat = $this->getHttpRequest()->getPost('lat');
//
//        $lng = $this->getHttpRequest()->getPost('lng');
//
//        $this->database->table('pokus')->insert([
//            'x' => $lat ,
//            'y' => $lng,
//        ]);
//
//    }

    public function handleAddFault() {

        $id_eletric = $this->getHttpRequest()->getPost('lat');

        $lng = $this->getHttpRequest()->getPost('lng');

        $this->database->table('pokus')->insert([
            'x' => $lat ,
            'y' => $lng,
        ]);

    }






    protected function createComponentInsertEletricForm(){

        $form = (new InsertEletricFormFactory()) -> create();
        $form->onSuccess[] = [$this, 'insertDeviceSucceeded'];
        $form['lat']->setValue($_GET["lat"]);
        $form['lng']->setValue($_GET["lng"]);
//        $this->flashMessage('Položka byla přidána.');
        return $form;


    }

    protected function createComponentInsertFaultForm(){

        $form = (new InsertFaultFormFactory()) -> create();
        $form->onSuccess[] = [$this, 'insertEletricFaultSucceeded'];
        return $form;

    }



    public function insertDeviceSucceeded($form, $values){



        $this->database->table('eletric')->insert([
            'nazev' => $values->nazev ,
            'ulice' => $values->ulice,
            'typ' => $values->typ,
            'svitidlo' => $values->svitidlo,
            'oznaceni' => $values->oznaceni,
            'pocet' => $values->pocet,
            'stav' => $values->stav,
            'stozar' => $values->stozar,
            'popis' => $values->popis,
            'lat' => $values->lat,
            'lng' => $values->lng,

        ]);



        $this->redirect('Eletric:');


    }

    public function insertEletricFaultSucceeded($form, $values){

        $data=
            ['description' => $values->description ,
            'datum' => $values->datum];

        $error_id = $this->database->table('error')->insert($data)->id_error;

        $this->database->table('eletric')
            ->where('id_eletric', $values->id_device)
            ->update([
                'error_id' => $error_id
            ]);

       $this->redirect('Eletric:default');

    }

    public function insertEletricFault($id){

        $this->database->table('eletric')
            ->where('id_eletric', $id) // must be called before update()
            ->update([
                'error_id' => $id
            ]);

    }


    protected function createComponentEditEletricForm(){

        $form = (new EditEletricFormFactory()) -> create();
        $form->onSuccess[] = [$this, 'updateDeviceSucceeded'];

        return $form;
    }

    public function updateDeviceSucceeded($form, $values){

        $this->database->table('eletric')
            ->where('id_eletric', $this->getParameter('id')) // must be called before update()
            ->update([

                'nazev' => $values->nazev ,
                'ulice' => $values->ulice,
                'typ' => $values->typ,
                'svitidlo' => $values->svitidlo,
                'oznaceni' => $values->oznaceni,
                'pocet' => $values->pocet,
                'stav' => $values->stav,
                'stozar' => $values->stozar,
                'popis' => $values->popis,
                'lat' => $values->lat,
                'lng' => $values->lng,

            ]);

        $this->flashMessage('Položka byla úspěšně editována.');

    }

    public function actionEdit($id){

        $values = $this->database->table('eletric')->get($id);
        if (!$values) {
            $this->error('Příspěvek nebyl nalezen');
        }

        $this['editEletricForm']->setDefaults([
            'nazev' => $values->nazev ,
            'ulice' => $values->ulice,
            'typ' => $values->typ,
            'svitidlo' => $values->svitidlo,
            'oznaceni' => $values->oznaceni,
            'pocet' => $values->pocet,
            'stav' => $values->stav,
            'stozar' => $values->stozar,
            'popis' => $values->popis,
            'lat' => $values->lat,
            'lng' => $values->lng,

        ]);


    }

    public function handleDelete($deviceId){

        $this->database->table('eletric')->where('id_eletric', $deviceId)->delete();
        $this->flashMessage('Zařízení bylo úspěšně odstraněno.', 'success');

    }



}