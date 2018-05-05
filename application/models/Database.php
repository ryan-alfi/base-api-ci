<?php /**
* 
*/
class Database extends CI_Model
{
	private $activeTable;
	private $where = array();
	private $whereIn = array();
	private $values = array();
	private $valuesBatch = array();
	private $limit;
	private $offset = 0;
	private $joinTables = array();
	private $joinFields = "*";
	private $joinOn;
	private $orderBy;
	private $like = array();
	private $orWhere = array();

	function __construct()
	{
		// parent:: __construct();
	}

	public function setTable($tableName){
		$this->activeTable = $tableName;
	}

	public function setLike($like){
		$this->like = $like;
	}

	public function setLimit($limit){
		$this->limit = $limit;
	}

	public function unsetLimit(){
		$this->limit = null;
	}

	public function setOffset($offset){
		$this->offset = $offset;
	}

	public function unsetOffset(){
		$this->offset = 0;
	}

	public function setOrWhere($orWhere) {
		$this->orWhere = $orWhere;
	}

	public function unsetOrWhere() {
		$this->orWhere = array();
	}

	public function setWhere($where){
		$this->where = $where;
	}

	public function unsetWhere(){
		$this->where = array();
	}

	public function setWhereIn($whereIn) {
		$this->whereIn = $whereIn;
	}

	public function unsetWhereIn() {
		$this->whereIn = array();
	}

	public function setOrderBy($orderBy){
		$this->orderBy = $orderBy;
	}

	public function unsetOrderBy(){
		$this->orderBy = null;
	}

	public function setValues($values){
		$this->values = $values;
	}

	public function unsetValues(){
		$this->values = array();
	}

	public function setNowDate($field) {
		$this->db->set($field, 'now()', false);
	}

	public function setJoinTables($tableName, $joinClause, $type){
		array_push($this->joinTables, array('tableName' => $tableName, 'joinClause' => $joinClause, 'type' => $type));
	}

	public function setJoinFields($fields){
		$this->joinFields = $fields;
	}

	public function getWhere(){
		return $this->where;
	}

	public function getData(){
		if ($this->where)
			$this->db->where($this->where);
		if ($this->orderBy) 
			$this->db->order_by($this->orderBy);
		if ($this->orWhere)
			$this->db->or_where($this->orWhere[0], $this->orWhere[1]);
		if ($this->limit)
			$this->db->limit($this->limit, $this->offset);
		if ($this->like)
			call_user_func_array(array($this->db, 'like'), $this->like);
		if ($this->whereIn)
			$this->db->where_in($this->whereIn["item"], $this->whereIn["values"]);
		$sql = $this->db->get($this->activeTable);
		return $sql->result();
	}

	public function getCount(){
		if ($this->where)
			$this->db->where($this->where);
		if ($this->orderBy)
			$this->db->order_by($this->orderBy);
		if ($this->limit)
			$this->db->limit($this->limit, $this->offset);
		if ($this->like)
			call_user_func_array(array($this->db, 'like'), $this->like);
		$count = $this->db->count_all_results($this->activeTable);
		return $count;
	}

	public function getDataJoin(){
		if ($this->where)
			$this->db->where($this->where);
		$this->db->select($this->joinFields);
		$this->db->from($this->activeTable);
		foreach ($this->joinTables as $key)
			$this->db->join($key['tableName'], $key['joinClause'], $key['type']);
		$sql = $this->db->get();
		return $sql->result();
	}

	public function insertData(){
		$result = $this->db->insert($this->activeTable, $this->values);
		if ($result)
			return $this->db->insert_id();
	}

	public function updateData(){
		if ($this->where)
			$this->db->where($this->where);
		if ($this->whereIn)
			$this->db->where_in($this->whereIn["item"], $this->whereIn["values"]);
		$result = $this->db->update($this->activeTable, $this->values);
		if ($result)
			return true;
	}

	public function deleteData(){
		if ($this->where)
			$this->db->where($this->where);
		$result = $this->db->delete($this->activeTable);
		if ($result)
			return true;
	}

} ?>