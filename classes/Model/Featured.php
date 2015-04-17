<?php
/**
 * Banner Model
 *
 * @package TheyWorkForYou
 */

namespace MySociety\TheyWorkForYou\Model;

class Featured {

    /**
     * DB handle
     */
    private $db;

    /**
     * Constructor
     *
     * @param Member   $member   The member to get positions for.
     */

    public function __construct()
    {
        $this->db = new \ParlDB;
    }

    public function get_title() {
        return $this->_get('featured_title');
    }

    public function set_title($title) {
        return $this->_set('featured_title', $title);
    }

    public function get_gid() {
        return $this->_get('featured_gid');
    }

    public function set_gid($gid) {
        return $this->_set('featured_gid', $gid);
    }

    public function get_related() {
        $related = $this->_get('featured_related');
        return explode(',', $related);
    }

    public function set_related($related) {
        $related = implode(',', $related);
        $this->_set('featured_related', $related);
    }

    private function _get($key) {
        $text = NULL;

        $q = $this->db->query(
            "SELECT value FROM editorial WHERE item = :key",
            array(
                ':key' => $key
            )
        );

        if ($q->rows) {
            $text = $q->field(0, 'value');
            if ( trim($text) == '' ) {
                $text = NULL;
            }
        }

        return $text;
    }

    private function _set($key, $value) {
        if ( trim($value) == '' ) {
            $value = NULL;
        }
        $check_q = $this->db->query(
            "SELECT value FROM editorial WHERE item = :key",
            array(
                ':key' => $key
            )
        );
        if ( $check_q->rows ) {
            $set_q = $this->db->query("UPDATE editorial set value = :value WHERE item = :key",
                array(
                    ':key' => $key,
                    ':value' => $value
                )
            );
        } else {
            $set_q = $this->db->query("INSERT INTO editorial (item, value ) VALUES (:key, :value)",
                array(
                    ':key' => $key,
                    ':value' => $value
                )
            );
        }

        if ( $set_q->success() ) {
            return true;
        }
        return false;
    }
}
