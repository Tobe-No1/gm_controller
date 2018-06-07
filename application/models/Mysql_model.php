<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
  FUN 数据库操作类
 */

class Mysql_model extends CI_Model {

    public function __construct($database = 'default') {
        parent::__construct();
        $this->ci_db = $this->load->database($database, TRUE);
    }

    /*
      FUN 查询扩展
      参数说明：
      函数说明：
      这个函数可以节省查询的时间，甚至多加条件
     */

    public function search($db = 'erp', $in, $sql, $where, $orderBy) {
        $where_sql = '';
        $orderBy_sql = '';
        $limit_sql = '';

        // 条件拼接		
        foreach ($where as $k => $v) {
            if (isset($in[$k]) && !empty($in[$k])) {
                $where_sql .= $v . ' ';
            }
        }

        // 排序拼接
        foreach ($orderBy as $k => $v) {
            if (isset($in[$k]) && !empty($in[$k])) {
                $orderBy_sql .= ',' . $v;
            }
        }
        if (!empty($orderBy_sql)) {
            $orderBy_sql = " ORDER BY " . ltrim($orderBy_sql, ',');
        }
        if (!empty($where_sql)) {
            $where_sql = " WHERE" . ltrim($where_sql, 'AND');
        }

        // 分页
        if (isset($in['limit']) && !empty($in['limit']) && isset($in['page'])) {
            $limit_sql = " LIMIT " . ($in['page'] * $in['limit']) . ",$in[limit]";
            $res['page'] = $in['page'];
            $res['limit'] = $in['limit'];
        }
        if (empty($limit_sql)) {
            $limit_sql = " LIMIT 0,10";
            $res['page'] = 0;
            $res['limit'] = 10;
        }
        // 开启查询
        if ($db == 'erp') {
            $res['data'] = $this->query($sql . $where_sql . $limit_sql . $orderBy_sql, 2);
            $res['count'] = $this->query($sql . $where_sql, 3);
        } else if ($db == 'ecshop') {
            $res['data'] = $this->ecs_query($sql . $where_sql . $limit_sql . $orderBy_sql, 2);
            $res['count'] = $this->ecs_query($sql . $where_sql, 3);
        }
        return $res;
    }

    // 启动事务
    public function trans_begin() {
        $this->ci_db->trans_begin();
    }

    // 事务状态
    public function trans_status() {
        return $this->ci_db->trans_status();
    }

    // 事务回滚
    public function trans_rollback() {
        $this->ci_db->trans_rollback();
    }

    // 提交事务
    public function trans_commit() {
        $this->ci_db->trans_commit();
    }

    // 返回表缀
    public function dbprefix($table) {
        return $this->ci_db->dbprefix($table);
    }

    public function _query_ecs($sql) {
        return $this->ecs_db->query($sql);
    }

    public function _query($sql) {
        return $this->ci_db->query($sql);
    }

    public function query($sql, $type = 1) {
        $query = $this->ci_db->query($sql);
        switch ($type) {
            case 1:
                $result = $query->row_array();
                break;
            case 2:
                $result = $query->result_array();
                break;
            case 3:
                $result = $query->num_rows();
                break;
        }
        return $result;
    }

    public function get_results($table, $where = '', $order = '', $limit1 = 0, $limit2 = 0, $select = '*') {
        $this->ci_db->select($select);
        $this->ci_db->from($this->ci_db->dbprefix($table));
        if ($where) {
            $this->ci_db->where($where);
        }
        if ($order) {
            $this->ci_db->order_by($order);
        }
        if ($limit2 > 0) {
            $this->ci_db->limit($limit2, $limit1);
        }
        $query = $this->ci_db->get();
        return $query->result_array();
    }
    
    public function get_rows($table, $where = array(1 => 1), $select = '*') {
        $query = $this->ci_db->select($select)
                ->from($this->ci_db->dbprefix($table))
                ->where($where)
                ->get();
        return $query->row_array();
    }

    public function get_row($table, $where = array(1 => 1), $select) {
        $query = $this->ci_db->select($select)
                ->from($this->ci_db->dbprefix($table))
                ->where($where)
                ->get();
        $result = $query->row_array();
        return $result[$select];
    }

    public function get_count($table, $where = array(1 => 1), $select = '*') {
        return $this->ci_db->select($select)
                        ->from($this->ci_db->dbprefix($table))
                        ->where($where)
                        ->count_all_results();
    }

    public function insert($table, $data) {
        $table = $this->ci_db->dbprefix($table);
        if (isset($data[0]) && is_array($data[0])) {
            $this->ci_db->insert_batch($table, $data);
        } else {
            $this->ci_db->insert($table, $data);
        }
        $this->ci_db->cache_delete_all();
        return $this->ci_db->insert_id();
    }

    public function update($table, $data, $where = '') {
        $table = $this->ci_db->dbprefix($table);
        if (isset($data[0]) && is_array($data[0])) {
            $this->ci_db->update_batch($table, $data, $where);
            if ($this->ci_db->affected_rows()) {
                $result = true;
            }
        } else {
            if (is_array($data) && count($data) > 0) {
                if ($where) {
                    $this->ci_db->where($where);
                }
                $result = $this->ci_db->update($table, $data);
            } else {
                if (!is_array($data)) {
                    $result = $this->ci_db->query('UPDATE ' . $table . ' SET ' . $data . ($where ? ' WHERE ' . $where : ''));
                }
            }
        }
        if (isset($result)) {
            $this->ci_db->cache_delete_all();
            return $result;
        }
        return false;
    }

    public function delete($table, $where = '') {
        $table = $this->ci_db->dbprefix($table);
        if ($where) {
            $this->ci_db->where($where);
        }
        $this->ci_db->delete($table);
        if ($this->ci_db->affected_rows()) {
            $this->ci_db->cache_delete_all();
            return true;
        }
        return false;
    }

}
