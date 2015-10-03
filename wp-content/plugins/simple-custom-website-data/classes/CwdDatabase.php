<?php

class CwdDatabase {
    protected $table_name = 'custom_website_data';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = &$wpdb;
        $this->table_name = $this->wpdb->prefix . $this->table_name;
    }

    public function createTable()
    {
        if($this->wpdb->get_var("show tables like '$this->table_name'") != $this->table_name)
        {
             $sql = "CREATE TABLE " . $this->table_name . " (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                ref tinytext NOT NULL,
                data tinytext NOT NULL,
                UNIQUE KEY id (id)
                );";

             require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
             dbDelta($sql);
        }
    }

    public function getAll()
    {
        return $this->wpdb->get_results(
                    "
                        SELECT *
                        FROM $this->table_name
                        ORDER BY ref
                    "
                    );
    }

    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
            "
                SELECT *
                FROM $this->table_name
                WHERE id = %s
            ",
            $id
                    )
            );
    }

    public function getByRef($ref)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
            "
                SELECT *
                FROM $this->table_name
                WHERE ref = %s
            ",
            $ref
                    )
            );
    }

    public function insert($ref, $data)
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                " INSERT INTO $this->table_name
                    ( ref, data )
                    VALUES ( %s, %s )
                ",
                         $ref, $data
                    )
                );
    }

    public function update($id, $data)
    {
        $up_data = array(
                        'data'=> $data
                        );

        $where = array(
                    'id' => $id
                    );

        $this->wpdb->update( $this->table_name, $up_data, $where, $format = null, $where_format = null );
    }

    public function delete($id)
    {
        $this->wpdb->query(
            $this->wpdb->prepare(
                    "DELETE FROM $this->table_name
                     WHERE  id = %d
                    "
                    ,$id)
                );
    }
}