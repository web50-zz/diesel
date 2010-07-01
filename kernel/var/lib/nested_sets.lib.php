<?php
/**
*	Библиотека для работы с деревьями
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	23-10-2008
*/
class nested_sets
{
	/**
	* @var	object	$di	Интерфейс данных.
	*/
	private $di;
	
	public function __construct($di)
	{
		$this->di = &$di;
	}
	
	public function add_node($pid = 0)
	{
		$pid = intval($pid);
		try
		{
			$values = array();
			$childs = $this->get_childs($pid);
			if (!empty($childs) AND ($last = array_pop($childs)))
			{
				$values['left'] = $last['right'] + 1;
				$values['right'] = $last['right'] + 2;
				$values['level'] = $last['level'];
				$lupd_query = 'UPDATE `' . $this->di->get_name() . '` SET `left` = `left` + 2 WHERE `left` > ' . $last['left'] . ' AND `right` > ' . $last['right'];
				$rupd_query = 'UPDATE `' . $this->di->get_name() . '` SET `right` = `right` + 2 WHERE `right` > ' . $last['right'];
			}
			elseif ($pid == 0)
			{
				$values['left'] = 1;
				$values['right'] = 2;
				$values['level'] = 1;
			}
			elseif ($pnode = $this->get_node($pid))
			{
				$values['left'] = $pnode['left'] + 1;
				$values['right'] = $pnode['left'] + 2;
				$values['level'] = $pnode['level'] + 1;
				$lupd_query = 'UPDATE `' . $this->di->get_name() . '` SET `left` = `left` + 2 WHERE `left` > ' . $pnode['left'];
				$rupd_query = 'UPDATE `' . $this->di->get_name() . '` SET `right` = `right` + 2 WHERE `right` >= ' . $pnode['right'];
			}
			else
			{
				throw new Exception('Undefined condition for adding new node');
			}
			
			$this->di->connector->query($lupd_query);
			$this->di->connector->query($rupd_query);
			$this->di->set_args($values, true);
			$this->di->_set();
			return true;
		}
		catch(Exception $e)
		{
			throw new Exception('Error while adding node: ' . $e->getMessage());
		}
	}
	
  	/**
	*	Премещаем ветвь с указанным CID в другую ветвь с указанным PID
	* 2006-09-04 Литвиненко А.С.
	*
	* @param	integer	$id	ID корневого узла ветви
	* @param	integer	$pid	ID нового родителя для перемещаемой ветки\
	* @param	integer	$pos	Позиция элемента в текущей ветке
	* @return	boolean	TRUE, или FALSE в случае ошибки.
	*/
  	public function move_node($id, $pid)
	{
		try
		{
			if ($id AND $pid)
			{
				if (!($B = $this->get_node($id)) || empty($B)) throw new Exception('Can`t get node by ID: '. $id);
				if (!($D = $this->get_node($pid)) || empty($D)) throw new Exception('Can`t get node by ID: '. $pid);
				if ($B['left'] <= $D['left'] AND $B['right'] >= $D['right']) throw new Exception('Нельзя перенести ветвь в саму себя.');
				
				$Lshift = $D['level'] - $B['level'] + 1;
				$Binterval = $B['left'] . ' AND ' . $B['right'];
				
				if ($D['right'] > $B['right'])
				{
					$Bshift = ' + ' . ($D['right'] - $B['right'] - 1);
					$Dinterval = ($B['right'] + 1) . ' AND ' . ($D['right'] - 1);
					$Dshift = ' - ' . ($B['right'] - $B['left'] + 1);
				}
				else
				{
					$Bshift = ' - ' . ($B['left'] - ($D['right']));
					$Dinterval = $D['right'] . ' AND ' . ($B['left'] - 1);
					$Dshift = ' + ' . ($B['right'] - $B['left'] + 1);
				}
			}
			elseif ($id AND $pid == 0)
			{
				if (!($B = $this->get_node($id)) || empty($B)) throw new Exception('Can`t get node by ID: '. $id);
				if (!($D = $this->get_last_node()) || empty($D)) throw new Exception('Can`t find last node');
				
				$Lshift = $D['level'] - $B['level'];
				
				$Binterval = $B['left'] . ' AND ' . $B['right'];
				$Bshift = ' + ' . ($D['right'] - $B['right']);
				
				$Dinterval = ($B['right'] + 1) . ' AND ' . $D['right'];
				$Dshift = ' - ' . ($B['right'] - $B['left'] + 1);
			}
			
			$sql = 'UPDATE `' . $this->di->get_name() . '` SET
			`level` = CASE
				WHEN `left` BETWEEN ' . $Binterval . ' THEN `level` + (' . $Lshift . ')
				ELSE `level` END,
			`left` = CASE
				WHEN `left` BETWEEN ' . $Binterval . ' THEN `left` ' . $Bshift . '
				WHEN `left` BETWEEN ' . $Dinterval . ' THEN `left` ' . $Dshift . '
				ELSE `left` END,
			`right` = CASE 
				WHEN `right` BETWEEN ' . $Binterval . ' THEN `right` ' . $Bshift . '
				WHEN `right` BETWEEN ' . $Dinterval . ' THEN `right` ' . $Dshift . '
				ELSE `right` END;';
			
			$this->di->connector->query($sql);
			return true;
		}
		catch(Exception $e)
		{
			throw new Exception('Error while moving node: ' . $e->getMessage());
		}
	}
	
	/**
	*	Удалить элемент и всех его потомков по ID
	* @param integer	$id	ID - элемента
	* @return	TRUE - если элемент удален, либо FALSE
	*/
	public function delete_node($id = NULL)
	{
		try
		{
			if (!($id > 0)) throw new Exception('Undefined node ID.');
			if (!($node = $this->get_node($id)) || empty($node)) throw new Exception('Can`t get node by ID: '. $id);
			
			$sql = 'DELETE FROM `' . $this->di->get_name() . '` WHERE `left` >= ' . $node['left'] . ' AND `right` <= ' . $node['right'];
			$this->di->connector->query($sql);
			
			// NOTE: Переиндексируем индексы дерева
			$k = $node['right'] - $node['left'] + 1;
			$sql = 'UPDATE `' . $this->di->get_name() . '` SET ';
			$sql.= '`left` = IF(`left` > ' . $node['left'] . ', `left` - ' . $k . ', `left`),';
			$sql.= '`right` = IF(`right` > ' . $node['left'] . ', `right` - ' . $k . ', `right`)';
			$sql.= ' WHERE `right` > ' . $node['right'];
			$this->di->connector->query($sql);
			return true;
		}
		catch(Exception $e)
		{
			throw new Exception('Error while deleting node: ' . $e->getMessage());
		}
	}
	
	/**
	*	Получить элемент по ID
	* @param integer	$id	ID - элемента
	* @return	Массив значений элемента
	*/
	public function get_node($id)
	{
		try
		{
			if (!($id > 0)) throw new Exception('Undefined node ID.');
			$sql = 'SELECT * FROM `' . $this->di->get_name() . '` WHERE  `id` = ' . $id;
			$this->di->connector->fetchMethod = PDO::FETCH_ASSOC;
			$this->di->_get($sql);
			return $this->di->get_results(0);
		}
		catch(Exception $e)
		{
			throw new Exception('Error while getting node: ' . $e->getMessage());
		}
	}
	
  	/**
	*	Получить массив left, right, level последнего элемента дерева
	* @return	mixed	Array, или FALSE в случае ошибки.
	* @see		this::move_node()
	*/
	public function get_last_node()
	{
		try
		{
			$sql = 'SELECT `left`, `right`, `level` FROM `' . $this->di->get_name() . '` ORDER BY `right` DESC LIMIT 1';
			$this->di->connector->fetchMethod = PDO::FETCH_ASSOC;
			$this->di->_get($sql);
			return $this->di->get_results(0);
		}
		catch(Exception $e)
		{
			throw new Exception('Error while getting last node: ' . $e->getMessage());
		}
	}
	
	public function get_childs($pid = 0, $level = 1)
	{
		try
		{
			if ($pid)
			{
				$q = 'SELECT sp1.* FROM';
				$q.= ' `' . $this->di->get_name() . '` AS sp1';
				$q.= ' LEFT JOIN `' . $this->di->get_name() . '` AS sp2 ON sp2.left < sp1.left AND sp2.right > sp1.right';
				$q.= ' WHERE';
				$q.= ' sp2.id = ' . $pid;
				if ($level) $q.= ' AND (sp1.level - sp2.level) <= ' . $level;
				if ($this->di->where) $q.= ' AND (' . str_replace('[table]', 'sp1.', $this->di->where) . ')';
				if (!$this->di->order)
					$q.= ' ORDER BY sp1.left';
				else
					$q.= ' ORDER BY sp1.' . str_replace('`', '', join(', sp1.', $this->di->order));
			}
			else
			{
				$q = 'SELECT * FROM `' . $this->di->get_name() . '`';
				$sql_where = '';
				if ($level) $sql_where[] = '`level` <= ' . $level;
				if ($this->di->where) $sql_where[] = '(' . str_replace('[table]', $this->di->get_name() . '.', $this->di->where) . ')';
				if ($sql_where) $q.= ' WHERE ' . join(' AND ', $sql_where);
				if (!$this->di->order)
					$q.= ' ORDER BY `left`';
				else
					$q.= ' ORDER BY ' . JOIN(', ', $this->di->order) . '';
			}
			$this->di->connector->fetchMethod = PDO::FETCH_ASSOC;
			$this->di->_get($q);
			return $this->di->get_results();
		}
		catch(Exception $e)
		{
			throw new Exception('Error while getting childs: ' . $e->getMessage());
		}
	}
	
	public function get_parent($id, $level = false)
	{
		try
		{
			if (!($id > 0)) throw new Exception('Undefined node ID.');
			$sql = 'SELECT parent.* FROM `' . $this->di->get_name() . '` as child';
			$sql.= ' LEFT JOIN `' . $this->di->get_name() . '` as parent';
			$sql.= ' ON parent.left < child.left AND parent.right > child.right';
			$sql.= ' WHERE parent.id IS NOT NULL AND child.id = ' . $id;
			$sql.= ' AND parent.level = ' . (($level > 0) ? $level :  'parent.level = child.level - 1');
			$this->di->connector->fetchMethod = PDO::FETCH_ASSOC;
			$this->di->_get($sql);
			return $this->di->get_results(0);
		}
		catch(Exception $e)
		{
			throw new Exception('Error while getting parent: ' . $e->getMessage());
		}
	}
	
	/**
	*	Получить всех родителей указанного узла
	* @param	integer	$id	ID узла
	* @param	boolean	$with_current	Если TRUE то возвращается вместе с указанным узлом
	* @return	массив родителей, либо FALSE 
	*/
	public function get_parents($id, $with_current = FALSE)
	{
		try
		{
			if (!($id > 0)) throw new Exception('Undefined node ID.');
			$q = 'SELECT parent.* FROM `' . $this->di->get_name() . '` as child';
			if ($with_current == FALSE)
				$q.= ' LEFT JOIN `' . $this->di->get_name() . '` as parent ON parent.left < child.left AND parent.right > child.right';
			else
				$q.= ' LEFT JOIN `' . $this->di->get_name() . '` as parent ON parent.left <= child.left AND parent.right >= child.right';
			$q.= ' WHERE parent.id IS NOT NULL AND child.id = ' . $id;
			$this->di->connector->fetchMethod = PDO::FETCH_ASSOC;
			$this->di->_get($q);
			return $this->di->get_results();
		}
		catch(Exception $e)
		{
			throw new Exception('Error while getting parents: ' . $e->getMessage());
		}
	}
}
?>
