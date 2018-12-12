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
        $dbName = "web_con";
       // $db = new PDO("mysql:host=localhost;dbname=$dbName", "root", "");
        $this->connection = new PDO("mysql:host=localhost;dbname=$dbName", "root", "");
    }

    /**
     * Nacist 1 zaznam z tabulky v DB.
     *
     * @param string $table_name - jméno tabulky
     * @param string $select_columns_string - jména sloupců oddělené čárkami, nebo jiné příkazy SQL
     * @param array $where_array - seznam podmínek<br/>
     * 							[] - column = sloupec; value - int nebo string nebo value_mysql = now(); symbol
     * @param string $limit_string - doplnit limit string
     */
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
            echo "Chyba v dotazu - PDOStatement::errorInfo(): ";
            printr($errors);
            echo "SQL dotaz: $query";
        }
    }

    /**
     * Nacist vsechny zaznamy z tabulky.
     * Poznamka: tato metoda je stejna jako DBSelectOne - lisi se to jen posledni casti Fetch vs FetchAll
     *
     * @param string $table_name
     * @param string $select_columns_string
     * @param array $where_array
     * @param string $limit_string
     * @param array			$order_by_array - pouze pole stringu: [0] => {[column] = "", [sort] => "DESC"}
     * @return mixed
     */
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
        $query = "select $select_columns_string from `".$table_name."` $where_pom $order_by_pom $limit_string;";
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
            echo "Chyba v dotazu - PDOStatement::errorInfo(): ";
            printr($errors);
            echo "SQL dotaz: $query";
        }
    }

}