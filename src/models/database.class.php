<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:16 PM
 */

class Database
{

    private $connection;

    public function __construct() {
        $db_server = "localhost";
        $db_name = "web_con";
        $db_user = "root";
        $db_pass = "";
        $this->connection = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_pass);
    }

    public function DBSelectOne($table_name, $select_columns_string, $where_array, $limit_string = "")
    {
        // PDO - MySQL
        //printr($where_array);

        // vznik chyby v PDO
        $mysql_pdo_error = false;

        // slozit si podminku s otaznikama
        $where_pom = "";

        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                // pridat AND
                if ($where_pom != "") $where_pom .= "AND ";

                $column = $item["column"];
                $symbol = $item["symbol"];

                if (key_exists("value", $item))
                    $value_pom = "?"; 						// budu to navazovat
                else if (key_exists("value_mysql", $item))
                    $value_pom = $item["value_mysql"]; 		// je to systemove, vlozit rovnou - POZOR na SQL injection, tady to muze projit


                $where_pom .= "`$column` $symbol  $value_pom ";
            }

        // doplnit slovo where
        if (trim($where_pom) != "") $where_pom = "where $where_pom";

        // 1) pripravit dotaz s dotaznikama
        $query = "select $select_columns_string from `".$table_name."` $where_pom $limit_string;";
        //echo "$query <br/>";

        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);

        // 3) NAVAZAT HODNOTY k otaznikum dle poradi od 1
        $bind_param_number = 1;

        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                if (key_exists("value", $item))
                {
                    $value = $item["value"];
                    //echo "navazuju value: $value jako number: $bind_param_number";

                    $statement->bindValue($bind_param_number, $value);  // vzdy musim dat value, abych si nesparoval promennou (to nechci)
                    $bind_param_number ++;
                }
            }

        // 4) provest dotaz
        $statement->execute();

        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // 6) nacist data a vratit
        if ($mysql_pdo_error == false)
        {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
        else
        {
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";

            return null;
        }
    }


    public function DBSelectAll($table_name, $select_columns_string, $where_array, $limit_string = "", $order_by_array = array())
    {
        // PDO - MySQL

        // vznik chyby v PDO
        $mysql_pdo_error = false;

        // slozit si podminku s otaznikama
        $where_pom = "";

        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                // pridat AND
                if ($where_pom != "") $where_pom .= "AND ";

                // pokud neexistuje klic column, tak preskocit
                if (!key_exists("column", $item))
                {
                    echo "asi chyba v metode DBSelectAll - chybi klic column <br/>";
                    continue;
                }

                $column = $item["column"];					// pozor na column, mohlo by projit SQL injection
                $symbol = $item["symbol"];

                if (key_exists("value", $item))
                    $value_pom = "?"; 						// budu to navazovat
                else if (key_exists("value_mysql", $item))
                    $value_pom = $item["value_mysql"]; 		// je to systemove, vlozit rovnou - POZOR na SQL injection, tady to muze projit


                //echo "`$column` $symbol  $value_pom ";
                $where_pom .= "`$column` $symbol  $value_pom ";
            }

        // doplnit slovo where
        if (trim($where_pom) != "") $where_pom = "where $where_pom";


        // pridat si order by - musim to tam dat primo, nelze to dat do prepared statements
        $order_by_pom = "";

        if ($order_by_array != null)
            foreach ($order_by_array as $index => $item)
            {
                $column = $item["column"];
                $sort = $item["sort"];

                // carku pred
                if (trim($order_by_pom != null))
                    $order_by_pom .= ", ";

                $order_by_pom .= "`$column` $sort";
            }

        // doplnit slovo order by
        if (trim($order_by_pom) != "") $order_by_pom = "order by $order_by_pom";


        // 1) pripravit dotaz s dotaznikama
        $query = "select $select_columns_string from `$table_name` $where_pom $order_by_pom $limit_string;";
        //echo $query;

        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);

        // 3) NAVAZAT HODNOTY k otaznikum dle poradi od 1
        $bind_param_number = 1;

        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                if (key_exists("value", $item))
                {
                    $value = $item["value"];
                    //echo "navazuju value: $value";

                    $statement->bindValue($bind_param_number, $value);  // vzdy musim dat value, abych si nesparoval promennou (to nechci)
                    $bind_param_number ++;
                }
            }

        // 4) provest dotaz
        $statement->execute();

        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // 6) nacist data a vratit
        if ($mysql_pdo_error == false)
        {
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        }
        else
        {
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";

            return null;
        }
    }

    public function DBInsert($table_name, $item)
    {
        // MySql
        $mysql_pdo_error = false;

        // SLOZIT TEXT STATEMENTU s otaznikama
        $insert_columns = "";
        $insert_values  = "";

        if ($item != null)
            foreach ($item as $column => $value)
            {
                // pridat carky
                if ($insert_columns != "") $insert_columns .= ", ";
                if ($insert_columns != "") $insert_values .= ", ";

                $insert_columns .= "`$column`";
                $insert_values .= "?";
            }

        // 1) pripravit dotaz s dotaznikama
        $query = "insert into `$table_name` ($insert_columns) values ($insert_values);";

        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);

        // 3) NAVAZAT HODNOTY k otaznikum dle poradi od 1
        $bind_param_number = 1;

        if ($item != null)
            foreach ($item as $column => $value)
            {
                $statement->bindValue($bind_param_number, $value);  // vzdy musim dat value, abych si nesparoval promennou (to nechci)
                $bind_param_number ++;
            }

        // 4) provest dotaz
        $statement->execute();

        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // 6) nacist ID vlozeneho zaznamu a vratit
        if ($mysql_pdo_error == false)
        {
            $item_id = $this->connection->lastInsertId();
            return $item_id;
        }
        else
        {
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";

            return null;
        }
    }

    public function DBInsertExpanded($table_name, $item)
    {
        // MySql
        $mysql_pdo_error = false;

        // SLOZIT TEXT STATEMENTU s otaznikama
        $insert_columns = "";
        $insert_values  = "";

        if ($item != null)
            foreach ($item as $row)
            {
                // pridat carky
                if ($insert_columns != "") $insert_columns .= ", ";
                if ($insert_columns != "") $insert_values .= ", ";

                $column = $row["column"];

                if (key_exists("value", $row))
                    $value_pom = "?"; 						// budu to navazovat
                else if (key_exists("value_mysql", $row))
                    $value_pom = $row["value_mysql"]; 		// je to systemove, vlozit rovnou - POZOR na SQL injection, tady to muze projit


                $insert_columns .= "`$column`";
                $insert_values .= "$value_pom";
            }

        // 1) pripravit dotaz s dotaznikama
        $query = "insert into `$table_name` ($insert_columns) values ($insert_values);";
        // echo $query;

        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);

        // 3) NAVAZAT HODNOTY k otaznikum dle poradi od 1
        $bind_param_number = 1;

        if ($item != null)
            foreach ($item as $row)
            {
                if (key_exists("value", $row))
                {
                    $value = $row["value"];
                    //echo "navazuju value: $value";

                    $statement->bindValue($bind_param_number, $value);  // vzdy musim dat value, abych si nesparoval promennou (to nechci)
                    $bind_param_number ++;
                }
            }

        // 4) provest dotaz
        $statement->execute();

        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // 6) nacist ID vlozeneho zaznamu a vratit
        if ($mysql_pdo_error == false)
        {
            $item_id = $this->connection->lastInsertId();
            return $item_id;
        }
        else
        {
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";

            return null;
        }
    }

    public function DBUpdate($table_name, $toUpdate, $where_array)
    {
        // vznik chyby v PDO
        $mysql_pdo_error = false;

        $update_values = "";
        if ($toUpdate != null) {
            foreach ($toUpdate as $column => $value)
            {
                // pridat carky
                if ($update_values != "") $update_values .= ", ";
                $update_values .= "$column = $value";
            }
        }
        if (trim($update_values) != "") $update_values = "set $update_values";

        // slozit si podminku s otaznikama
        $where_pom = "";
        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                // pridat AND
                if ($where_pom != "") $where_pom .= "AND ";
                $column = $item["column"];
                $symbol = $item["symbol"];
                if (key_exists("value", $item))
                    $value_pom = "?"; 						// budu to navazovat
                else if (key_exists("value_mysql", $item))
                    $value_pom = $item["value_mysql"]; 		// je to systemove, vlozit rovnou - POZOR na SQL injection, tady to muze projit
                $where_pom .= "`$column` $symbol  $value_pom ";
            }
        // doplnit slovo where
        if (trim($where_pom) != "") $where_pom = "where $where_pom";
        // 1) pripravit dotaz s dotaznikama
        $query = "update `".$table_name."` $update_values $where_pom ;";
        //echo "$query <br/>";
        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);
        // 3) NAVAZAT HODNOTY k otaznikum dle poradi od 1
        $bind_param_number = 1;
        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                if (key_exists("value", $item))
                {
                    $value = $item["value"];
                    //echo "navazuju value: $value jako number: $bind_param_number";
                    $statement->bindValue($bind_param_number, $value);  // vzdy musim dat value, abych si nesparoval promennou (to nechci)
                    $bind_param_number ++;
                }
            }
        // 4) provest dotaz
        $statement->execute();
        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);
        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }
        // 6) nacist data a vratit
        if ($mysql_pdo_error == false)
        {
            return null;
        }
        else
        {
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
        }
    }

    public function DBUpdateExpanded($table_name, $item_array, $where_array , $limit_string = ""){
        // ITEM podminky ******************************************
        $item_temp = "";

        if ($item_array != null){
            foreach ($item_array as $column => $value){

                if ($item_temp != "") $item_temp .= ",";
                $item_temp .= "`$column`=?";
            }
        }

        // WHERE podminky ******************************************
        $where_temp = "";

        if ($where_array != null){
            foreach ($where_array as $index => $item){

                if ($where_temp != "") $where_temp .= "AND ";

                $column = $item["column"];
                $symbol = $item["symbol"];

                $where_temp .= "`$column` $symbol ? ";
            }
        }

        if (trim($where_temp) != "") $where_temp = "where $where_temp";

        // Priprava dotazu ******************************************


        $query = "update `$table_name` set $item_temp $where_temp $limit_string;";
        $statement = $this->connection->prepare($query);

        $bind_num = 1;

        // navazani menenych hodnot
        if ($item_array != null){
            foreach ($item_array as $column => $value){
                $statement->bindValue($bind_num, $value);
                $bind_num++;
            }
        }

        // navazani hodnot where
        if ($where_array != null){
            foreach ($where_array as $index => $item){
                $value = $item["value"];
                $statement->bindValue($bind_num, $value);
                $bind_num++;
            }
        }

        // Vykonani dotazu
        $statement->execute();

        // Pripadny vypis chyb
        $errors = $statement->errorInfo();
        if ($errors[0] + 0 > 0){
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
        }

    }

    public function DBDelete($table_name, $where_array, $limit_string)
    {
        // PDO - MySQL

        // vznik chyby v PDO
        $mysql_pdo_error = false;

        // slozit si podminku s otaznikama
        $where_pom = "";

        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                // pridat AND
                if ($where_pom != "") $where_pom .= "AND ";

                // pokud neexistuje klic column, tak preskocit
                if (!key_exists("column", $item))
                {
                    echo "asi chyba v metode DBDelete - chybi klic column <br/>";
                    continue;
                }

                $column = $item["column"];
                $symbol = $item["symbol"];

                if (key_exists("value", $item))
                    $value_pom = "?"; 						// budu to navazovat
                else if (key_exists("value_mysql", $item))
                    $value_pom = $item["value_mysql"]; 		// je to systemove, vlozit rovnou - POZOR na SQL injection, tady to muze projit


                //echo "`$column` $symbol  $value_pom ";
                $where_pom .= "`$column` $symbol  $value_pom ";
            }

        // doplnit slovo where
        if (trim($where_pom) != "") $where_pom = "where $where_pom";

        // 1) pripravit dotaz s dotaznikama
        $query = "delete from `".$table_name."` $where_pom $limit_string;";
        //echo $query;

        // 2) pripravit si statement
        $statement = $this->connection->prepare($query);

        // 3) NAVAZAT HODNOTY k otaznikum dle poradi od 1
        $bind_param_number = 1;

        if ($where_array != null)
            foreach ($where_array as $index => $item)
            {
                if (key_exists("value", $item))
                {
                    $value = $item["value"];
                    //echo "navazuju value: $value";

                    $statement->bindValue($bind_param_number, $value);  // vzdy musim dat value, abych si nesparoval promennou (to nechci)
                    $bind_param_number ++;
                }
            }

        // 4) provest dotaz
        $statement->execute();

        // 5) kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // 6) nacist data a vratit
        if ($mysql_pdo_error == false)
        {
            // tady nevim, co bych vracel - smazani se podarilo
        }
        else
        {
            echo "Error in query: ".$query;
            echo "<pre>";
            var_dump($errors);
            echo "</pre>";
        }
    }
}