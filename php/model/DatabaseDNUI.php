<?php

/**
 * All about database and SQL to search image
 *
 * @author Nicearma
 */
class DatabaseDNUI
{

    private $db;
    private $prefix;

    function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->prefix = $wpdb->prefix;
    }


    /**
     * Get all image from the database with options
     */
    public function countImages()
    {

        $sql = 'SELECT count(*) FROM ' . $this->prefix . 'posts, ' . $this->prefix . "postmeta where " . $this->prefix . "posts.post_type='attachment' and " . $this->prefix . "posts.post_mime_type like  'image%' and " . $this->prefix . "posts.id=" . $this->prefix . "postmeta.post_id and " . $this->prefix . "postmeta.meta_key='_wp_attachment_metadata' ";

        return $this->db->get_results($sql, "ARRAY_A");

    }


    /**
     * Get all image from the database with options
     */
    public function getImages($i, $max, $order)
    {

        $sql = 'SELECT id FROM ' . $this->prefix . 'posts, ' . $this->prefix . "postmeta where " . $this->prefix . "posts.post_type='attachment' and " . $this->prefix . "posts.post_mime_type like  'image%' and " . $this->prefix . "posts.id=" . $this->prefix . "postmeta.post_id and " . $this->prefix . "postmeta.meta_key='_wp_attachment_metadata' ";
        $last = ' ORDER BY  `' . $this->prefix . 'postmeta`.`meta_id`';
        if ($order == 0) {
            $last .= ' ASC ';
        } else {
            $last .= ' DESC ';
        }
        $sql .= $last . ' LIMIT ' . (($i-1) * $max) . ", $max";

        return $this->db->get_results($sql, "ARRAY_A");

    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }


    public function delete($imageDNUI, $sizeName, $optionsDNUI)
    {
        $status = new StatusDNUI();
        $uploadDir = wp_upload_dir();
        $attachment = wp_get_attachment_metadata($imageDNUI->getId());
        $basedir = $uploadDir['basedir'];

        if ($sizeName == "original") {
            wp_delete_attachment($imageDNUI->getId());
            $status->setUsed(2); //2 -> deleted
            $status->setInServer(0);
        } else {

            clearstatcache();
            $srcInServer = $basedir . '/' . $imageDNUI->getImageSizes()[$sizeName]->getSrcSizeImage();

            if (!file_exists($srcInServer)) {

                unset($attachment["sizes"][$sizeName]);
                wp_update_attachment_metadata($imageDNUI->getId(), $attachment);

                $status->setUsed(2);
                $status->setInServer(0);
            } else {

                if (@unlink($srcInServer)) {
                    clearstatcache();
                    if (!file_exists($srcInServer)) {
                        unset($attachment["sizes"][$sizeName]);
                        wp_update_attachment_metadata($imageDNUI->getId(), $attachment);
                        $status->setUsed(2);
                        $status->setInServer(0);
                    } else {
                        $status->setUsed(-3); //-3 -> error
                        $status->setInServer(1);
                    }

                }
            }


        }
        return $status;


    }


    /**
     * Get the post information (this is use for the backup)
     *
     * @param type $id Id of the post
     * @return type
     */
    public function getRowPost($id)
    {

        $sql = 'SELECT * FROM ' . $this->prefix . 'posts where id=' . $id . ';';
        return $this->db->get_results($sql, "ARRAY_A");
    }

    /**
     * Get the postmeta information (This is use for the backup file)
     *
     * @param type $id Id of the post
     * @return type
     */
    public function getRowPostMeta($id)
    {
        $sql = 'SELECT * FROM ' . $this->prefix . 'postmeta where post_id=' . $id . ';';
        return $this->db->get_results($sql, "ARRAY_A");
    }



}
