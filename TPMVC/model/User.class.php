<?php

class User extends Model {

   protected static $table_name = 'USER';

   // load all users from Db
   public static function getList() {
      $stm = parent::exec('USER_LIST');
      return $stm->fetchAll();
   }

   //load one user by id
   public static function getById($id) {
      $stm = parent::exec('USER_GET_WITH_ID', [':id' => $id]);
      return $stm->fetch();
   }

   public function getProperties(){
      return $this->props;
   }

}