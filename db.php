<?
Class Connect
{
    public $host = "localhost";
    public $user = "root";
    public $password = "";
    public $db = "books_list";
    public $charset = "utf8";
    public $pdo = "";

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $opt = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $this->pdo = new PDO($dsn, $this->user, $this->password, $opt);
    }
}


Class Db extends Connect
{
    public $table_name = '';

    /**
     * добавление записи в таблицу
     * @param array $data массив данных для сохранения
     * @return Boolen
     */
    public function insert($data)
    {
        $data['create_at'] = Date('Y-m-d H:i:s');
        $fields = $this->set_fields($data);
        $sql = "INSERT INTO `{$this->table_name}` SET ".$fields;
        $stmt = $this->pdo->prepare( $sql );

        return $stmt->execute($data);
    }

    public function delete($data)
    {
        $sql = "DELETE FROM `{$this->table_name}`".' WHERE id=:id';

        $stmt = $this->pdo->prepare( $sql );
        return $stmt->execute($data);
    }

    public function update($data)
    {
        $fields = $this->set_fields($data);
        $sql = "UPDATE `{$this->table_name}` SET ".$fields.' WHERE id=:id';

        $stmt = $this->pdo->prepare( $sql );
        return $stmt->execute($data);
    }

    public function set_fields( $items, $delimiter = "," ){
        $str = array();
        if(empty($items)) return "";
        foreach ($items as $key=>$item){
            $str[] = "`".$key."`=:".$key;
        }
        return implode($delimiter, $str );
    }

    public function get_count( $where = array() )
    {

        $sql = "SELECT count(*) FROM {$this->table_name}";
        if( count( $where) > 0 ){
            $fields = $this->set_fields($where, " AND ");
            $sql .= " WHERE ".$fields;
        }

        $smtp = $this->pdo->prepare($sql);
        $smtp->execute($where);
        $result = $smtp->fetch( PDO::FETCH_NUM );

        return (int)$result[0];
    }


    public function get_all($order = "id asc")
    {
        $sql = "SELECT * FROM `{$this->table_name}` ORDER BY $order";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * (new City())->get_one(['id' => 5])
     */
    public function get_one($where = [], $order = "id asc")
    {
        $sql = "SELECT * FROM `{$this->table_name}`";
        if( count( $where) > 0 ){
            $fields = $this->set_fields($where, " AND ");

            $sql .= " WHERE ".$fields;
        }
        $sql .= " ORDER BY $order";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($where);
        $result = $stmt->fetch();
        return $result;

    }

}