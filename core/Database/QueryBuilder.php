<?php

namespace Core\Database;

use PDO;
use PDOException;

class QueryBuilder
{
    protected $pdo;
    protected $table;
    protected $select = ['*'];
    protected $joins = [];
    protected $wheres = [];
    protected $orders = [];
    protected $groups = [];
    protected $havings = [];
    protected $limit;
    protected $offset;
    protected $bindings = [];
    protected $model;
    
    public function __construct(PDO $pdo, string $table = null, $model = null)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->model = $model;
    }
    
    /**
     * Set the table for the query
     */
    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Set the columns to select
     */
    public function select(...$columns)
    {
        if (is_array($columns[0])) {
            $this->select = $columns[0];
        } else {
            $this->select = $columns;
        }
        return $this;
    }
    
    /**
     * Add a basic where clause
     */
    public function where($column, $operator = null, $value = null)
    {
        // Handle 2-argument case: where('column', 'value')
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        // 3-argument case: where('column', 'operator', 'value') - already correct
        
        // Convert boolean values to integers for SQLite compatibility
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        
        $this->wheres[] = [
            'type' => 'basic',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'and'
        ];
        
        $this->bindings[] = $value;
        return $this;
    }
    
    /**
     * Add an "or where" clause
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        
        // Convert boolean values to integers for SQLite compatibility
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        
        $this->wheres[] = [
            'type' => 'basic',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'or'
        ];
        
        $this->bindings[] = $value;
        return $this;
    }
    
    /**
     * Add a "where in" clause
     */
    public function whereIn($column, array $values)
    {
        $this->wheres[] = [
            'type' => 'in',
            'column' => $column,
            'values' => $values,
            'boolean' => 'and'
        ];
        
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }
    
    /**
     * Add a "where not in" clause
     */
    public function whereNotIn($column, array $values)
    {
        $this->wheres[] = [
            'type' => 'not_in',
            'column' => $column,
            'values' => $values,
            'boolean' => 'and'
        ];
        
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }
    
    /**
     * Add a "where null" clause
     */
    public function whereNull($column)
    {
        $this->wheres[] = [
            'type' => 'null',
            'column' => $column,
            'boolean' => 'and'
        ];
        
        return $this;
    }
    
    /**
     * Add a "where not null" clause
     */
    public function whereNotNull($column)
    {
        $this->wheres[] = [
            'type' => 'not_null',
            'column' => $column,
            'boolean' => 'and'
        ];
        
        return $this;
    }
    
    /**
     * Add a "where between" clause
     */
    public function whereBetween($column, array $values)
    {
        $this->wheres[] = [
            'type' => 'between',
            'column' => $column,
            'values' => $values,
            'boolean' => 'and'
        ];
        
        $this->bindings = array_merge($this->bindings, $values);
        return $this;
    }
    
    /**
     * Add a "where like" clause
     */
    public function whereLike($column, $value)
    {
        return $this->where($column, 'LIKE', $value);
    }
    
    /**
     * Add order by clause
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = [
            'column' => $column,
            'direction' => strtolower($direction) === 'desc' ? 'DESC' : 'ASC'
        ];
        
        return $this;
    }
    
    /**
     * Add group by clause
     */
    public function groupBy(...$columns)
    {
        foreach ($columns as $column) {
            $this->groups[] = $column;
        }
        
        return $this;
    }
    
    /**
     * Add having clause
     */
    public function having($column, $operator = null, $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        
        $this->havings[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        
        $this->bindings[] = $value;
        return $this;
    }
    
    /**
     * Set limit
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Set offset
     */
    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * Set limit and offset (pagination)
     */
    public function take(int $limit)
    {
        return $this->limit($limit);
    }
    
    /**
     * Set offset (pagination)
     */
    public function skip(int $offset)
    {
        return $this->offset($offset);
    }
    
    /**
     * Add join clause
     */
    public function join($table, $first, $operator = null, $second = null)
    {
        return $this->addJoin('inner', $table, $first, $operator, $second);
    }
    
    /**
     * Add left join clause
     */
    public function leftJoin($table, $first, $operator = null, $second = null)
    {
        return $this->addJoin('left', $table, $first, $operator, $second);
    }
    
    /**
     * Add right join clause
     */
    public function rightJoin($table, $first, $operator = null, $second = null)
    {
        return $this->addJoin('right', $table, $first, $operator, $second);
    }
    
    /**
     * Add join to the query
     */
    protected function addJoin($type, $table, $first, $operator = null, $second = null)
    {
        if (func_num_args() === 4) {
            $second = $operator;
            $operator = '=';
        }
        
        $this->joins[] = [
            'type' => $type,
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second
        ];
        
        return $this;
    }
    
    /**
     * Execute the query and get all results
     */
    public function get()
    {
        $sql = $this->toSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If we have a model, hydrate the results
        if ($this->model) {
            return array_map(function($row) {
                return new $this->model($row);
            }, $results);
        }
        
        return $results;
    }
    
    /**
     * Execute the query and get the first result
     */
    public function first()
    {
        $sql = $this->toSql() . ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return null;
        }
        
        // If we have a model, hydrate the result
        if ($this->model) {
            return new $this->model($result);
        }
        
        return $result;
    }
    
    /**
     * Find a record by ID
     */
    public function find($id)
    {
        return $this->where('id', $id)->first();
    }
    
    /**
     * Get count of records
     */
    public function count($column = '*')
    {
        $sql = $this->buildSelectSql(['COUNT(' . $column . ') as count']);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int) $result['count'];
    }
    
    /**
     * Check if records exist
     */
    public function exists()
    {
        return $this->count() > 0;
    }
    
    /**
     * Get the maximum value of a column
     */
    public function max($column)
    {
        return $this->aggregate('MAX', $column);
    }
    
    /**
     * Get the minimum value of a column
     */
    public function min($column)
    {
        return $this->aggregate('MIN', $column);
    }
    
    /**
     * Get the average value of a column
     */
    public function avg($column)
    {
        return $this->aggregate('AVG', $column);
    }
    
    /**
     * Get the sum of a column
     */
    public function sum($column)
    {
        return $this->aggregate('SUM', $column);
    }
    
    /**
     * Execute an aggregate function
     */
    protected function aggregate($function, $column)
    {
        $sql = $this->buildSelectSql([$function . '(' . $column . ') as result']);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['result'];
    }
    
    /**
     * Insert a new record
     */
    public function insert(array $data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    /**
     * Insert a new record and return the ID
     */
    public function insertGetId(array $data)
    {
        if ($this->insert($data)) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update records
     */
    public function update(array $data)
    {
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "{$column} = ?";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause);
        $sql .= $this->buildWhereSql();
        
        $bindings = array_merge(array_values($data), $this->bindings);
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($bindings);
    }
    
    /**
     * Delete records
     */
    public function delete()
    {
        $sql = "DELETE FROM {$this->table}";
        $sql .= $this->buildWhereSql();
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }
    
    /**
     * Build the complete SQL query
     */
    public function toSql()
    {
        return $this->buildSelectSql($this->select);
    }
    
    /**
     * Build select SQL
     */
    protected function buildSelectSql(array $columns)
    {
        $sql = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $this->table;
        
        // Add joins
        foreach ($this->joins as $join) {
            $sql .= ' ' . strtoupper($join['type']) . ' JOIN ' . $join['table'];
            $sql .= ' ON ' . $join['first'] . ' ' . $join['operator'] . ' ' . $join['second'];
        }
        
        // Add where clauses
        $sql .= $this->buildWhereSql();
        
        // Add group by
        if (!empty($this->groups)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }
        
        // Add having
        if (!empty($this->havings)) {
            $sql .= ' HAVING ';
            $havingClauses = [];
            foreach ($this->havings as $having) {
                $havingClauses[] = $having['column'] . ' ' . $having['operator'] . ' ?';
            }
            $sql .= implode(' AND ', $havingClauses);
        }
        
        // Add order by
        if (!empty($this->orders)) {
            $sql .= ' ORDER BY ';
            $orderClauses = [];
            foreach ($this->orders as $order) {
                $orderClauses[] = $order['column'] . ' ' . $order['direction'];
            }
            $sql .= implode(', ', $orderClauses);
        }
        
        // Add limit and offset
        if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
        }
        
        if ($this->offset) {
            $sql .= ' OFFSET ' . $this->offset;
        }
        
        return $sql;
    }
    
    /**
     * Build where SQL clause
     */
    protected function buildWhereSql()
    {
        if (empty($this->wheres)) {
            return '';
        }
        
        $sql = ' WHERE ';
        $whereClauses = [];
        
        foreach ($this->wheres as $i => $where) {
            $clause = '';
            
            // Add boolean operator (AND/OR) except for the first clause
            if ($i > 0) {
                $clause .= ' ' . strtoupper($where['boolean']) . ' ';
            }
            
            switch ($where['type']) {
                case 'basic':
                    $clause .= $where['column'] . ' ' . $where['operator'] . ' ?';
                    break;
                    
                case 'in':
                    $placeholders = implode(',', array_fill(0, count($where['values']), '?'));
                    $clause .= $where['column'] . ' IN (' . $placeholders . ')';
                    break;
                    
                case 'not_in':
                    $placeholders = implode(',', array_fill(0, count($where['values']), '?'));
                    $clause .= $where['column'] . ' NOT IN (' . $placeholders . ')';
                    break;
                    
                case 'null':
                    $clause .= $where['column'] . ' IS NULL';
                    break;
                    
                case 'not_null':
                    $clause .= $where['column'] . ' IS NOT NULL';
                    break;
                    
                case 'between':
                    $clause .= $where['column'] . ' BETWEEN ? AND ?';
                    break;
            }
            
            $whereClauses[] = $clause;
        }
        
        return $sql . implode('', $whereClauses);
    }
    
    /**
     * Create a new query builder instance
     */
    public function newQuery()
    {
        return new static($this->pdo, $this->table, $this->model);
    }
    
    /**
     * Clone the query builder
     */
    public function clone()
    {
        return clone $this;
    }
} 