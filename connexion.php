<?php
    class connect extends PDO{
        const host = "localhost";
        const user = "root";
        const db = "gestioncommande";
        const pwd = "";

        public function __construct()
        {
            try{
                parent::__construct("mysql:host=".self::host.";dbname=".self::db,self::user,self::pwd);
               // echo "bon!";
            }catch(Exception $e)
            {
                die("Erreur de connexion !".$e->getMessage());
            }
        }
    }


?>