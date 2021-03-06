<?php

namespace FrontModule;

use Nette;


class EletricPresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct();
        $this->database = $database;

    }

    public function renderDefault()
    {
        $this->myRenderDefault(null);
    }

    public function renderShow()
    {
        $this->template->eletric = $this->database->table('eletric');


    }

    public function renderInfo($id){
        $this->template->eletric = $this->database->table('eletric')->where('id_eletric', $id);
    }

    public function renderFault()
    {


    }


    public function actionFault($id){

        $this['insertFaultForm']->setDefaults([
            'id_device' => $id
        ]);

    }

    protected function createComponentInsertFaultForm(){

        $form = (new InsertFaultFormFactory()) -> create();
        $form->onSuccess[] = [$this, 'insertEletricFaultSucceeded'];
        return $form;

    }

    public function insertEletricFaultSucceeded($form, $values){

        $data=
            ['description' => $values->description ,
                'datum' => $values->datum,
                'email' => $values->email,
            ];

        $error_id = $this->database->table('error')->insert($data)->id_error;

        $this->database->table('eletric')
            ->where('id_eletric', $values->id_device)
            ->update([
                'error_id' => $error_id
            ]);

        $this->redirect('Eletric:default');

    }

    protected function createComponentFiltrEletricForm(){

        $form = (new FiltrEletricFormFactory()) -> create();
        $form->onSuccess[] = [$this, 'filtrDeviceSucceeded'];

        return $form;
    }

    private function myRenderDefault($value) {
        if(!isset($this->template->eletric))
        {
            if(!$value)
            {
                $this->template->eletric = $this->database->table('eletric');
            }
            else {
                $this->template->eletric = $this->database->table('eletric')->where('ulice', $value);
            }
        }
    }

    public function filtrDeviceSucceeded($form, $values){
        $this->myRenderDefault($values->ulice);
    }



}