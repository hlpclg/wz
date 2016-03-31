<?php

/**
 *
 * User: www.zheyitianShi.Com
 * Date: 2015/1/18
 * Time: 0:01
 */
class DBUtil
{


    public static $TABLE_ORDER_FORM = "mon_orderform";
    public static $TABLE_ORDER_ITEM = "mon_orderform_item";
    public static $TABLE_ORDER_ORDER = "mon_orderform_order";
    public static $TABLE_ORDER_SETTING = "mon_orderform_setting";
    public static $TABLE_ORDER_TEMPLATE = "mon_orderform_template";

    public static function findById($table, $id)
    {
        return pdo_fetch("select * from " . tablename($table) . " where id=:id", array(':id' => $id));
    }


    public static function findUnique($table, $params = array())
    {


        if (!empty($params)) {
            $where = " where ";
            $index = 0;
            foreach ($params as $key => $value) {

                $where .= substr($key, 1) . "=" . $key . " ";

                if ($index < count($params) - 1) {
                    $where .= " and ";
                }
                $index++;

            }
        }

        return pdo_fetch("select * from " . tablename($table) . $where, $params);

    }

    public static function  findList($table, $params = array())
    {


        if (!empty($params)) {
            $where = " where ";
            $index = 0;
            foreach ($params as $key => $value) {

                $where .= substr($key, 1) . "=" . $key . " ";

                if ($index < count($params) - 1) {
                    $where .= " and ";
                }
                $index++;

            }
        }

        return pdo_fetchall("select * from " . tablename($table) . $where, $params);
    }


    public static function  findListEx($table, $fileds, $params = array())
    {


        if (!empty($params)) {
            $where = " where ";
            $index = 0;
            foreach ($params as $key => $value) {

                $where .= substr($key, 1) . "=" . $key . " ";

                if ($index < count($params) - 1) {
                    $where .= " and ";
                }
                $index++;

            }
        }

        return pdo_fetchall("select " . $fileds . " from " . tablename($table) . $where, $params);
    }


    public static function  create($table, $data = array())
    {
        return pdo_insert($table, $data);

    }

    public static function  update($table, $data = array(), $params = array())
    {
        return pdo_update($table, $data, $params);

    }

    public static function  updateById($table, $data = array(), $id)
    {
        return pdo_update($table, $data, array('id' => $id));

    }


    public static function  deleteByid($table, $id)
    {
        return pdo_delete($table, array('id' => $id));
    }

    public static function  delete($table, $params = array())
    {
        return pdo_delete($table, $params);
    }


}